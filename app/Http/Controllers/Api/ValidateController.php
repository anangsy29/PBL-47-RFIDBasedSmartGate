<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\RFIDtag;

class ValidateController extends Controller
{
    public function validateTag(Request $request)
    {
        $tagUid = $request->input('tag_uid');

        if (!$tagUid) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tag UID is required'
            ], 400);
        }

        // Cari tag di tabel rfid_tags berdasarkan tag_uid
        $rfidTag = RFIDtag::with(['user', 'vehicle'])
            ->where('tag_uid', $tagUid)
            ->where('status', 'active') // opsional, jika kamu hanya ingin valid tag yang aktif
            ->first();

        if ($rfidTag) {
            $response = app(FCMController::class)->sendVerificationNotification(
                new Request([
                    'user_id' => $rfidTag->user_id,
                    'tag_uid' => $rfidTag->tag_uid,
                ])
            );

            // Logging hasilnya
            \Log::info("📨 Notifikasi response: ", [
                'status' => $response->getStatusCode(),
                'body' => $response->getContent()
            ]);

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
            return response()->json([
                'status' => 'invalid',
                'message' => 'Tag not found or inactive'
            ], 404);
        }
    }
}
