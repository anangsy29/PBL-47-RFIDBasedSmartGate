<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\RFIDtag;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Api\FCMController;

class ValidateController extends Controller
{
    public function validateTag(Request $request)
    {
        $tagUid = strtoupper(trim($request->input('tag_uid')));

        Log::info("📥 Tag UID diterima: " . $tagUid);

        if (!$tagUid) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tag UID is required'
            ], 400);
        }

        // Cari tag dengan case-insensitive match dan pastikan aktif
        $rfidTag = RFIDtag::with(['user', 'vehicle'])
            ->whereRaw('UPPER(tag_uid) = ?', [$tagUid])
            ->where('status', 'active') // pastikan kolom status-nya sesuai
            ->first();

        if ($rfidTag) {
            Log::info("✅ Tag ditemukan: " . $rfidTag->tag_uid);

            try {
                $response = app(FCMController::class)->sendVerificationNotification(
                    new Request([
                        'user_id' => $rfidTag->user_id,
                        'tag_uid' => $rfidTag->tag_uid,
                    ])
                );

                Log::info("📨 Notifikasi response: ", [
                    'status' => $response->getStatusCode(),
                    'body' => $response->getContent()
                ]);
            } catch (\Throwable $e) {
                Log::error("❌ Gagal mengirim notifikasi FCM", [
                    'error' => $e->getMessage()
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Tag valid',
                'tag' => [
                    'tag_uid' => $rfidTag->tag_uid,
                    'tags_id' => $rfidTag->tags_id,
                ],
                'user' => [
                    'id' => $rfidTag->user?->user_id,
                    'name' => $rfidTag->user?->name,
                ],
                'vehicle' => [
                    'id' => $rfidTag->vehicle?->vehicles_id,
                    'plate_number' => $rfidTag->vehicle?->plate_number ?? 'Unknown'
                ]
            ], 200);
        } else {
            \Log::warning("❌ Tag tidak ditemukan atau tidak aktif: " . $tagUid);

            return response()->json([
                'status' => 'invalid',
                'message' => 'Tag not found or inactive'
            ], 404);
        }
    }
}
