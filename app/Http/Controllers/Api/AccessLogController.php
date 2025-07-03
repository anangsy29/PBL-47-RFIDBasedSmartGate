<?php

namespace App\Http\Controllers\Api;

use App\Models\AccessLog;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RFIDtag;

class AccessLogController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tags_id' => 'required|exists:rfid_tags,tags_id',
            'status' => 'required|in:allowed,denied',
            'note' => 'nullable|string',
        ]);

        $accessLog = AccessLog::create([
            'tags_id' => $validated['tags_id'],
            'accessed_at' => now(),
            'status' => $validated['status'],
            'note' => $validated['note'] ?? null,
        ]);

        return response()->json(['message' => 'Access logged successfully', 'data' => $accessLog], 201);
    }

    public function storeOutput(Request $request)
    {
        $request->validate([
            'rfidTagID' => 'required|string',
            'status' => 'required|string',
            'message' => 'nullable|string',
        ]);

        // Ambil data tag dari tag_uid
        $rfidTag = RFIDtag::where('tag_uid', $request->rfidTagID)->first();

        if (!$rfidTag) {
            return response()->json([
                'success' => false,
                'message' => 'RFID tag not found'
            ], 404);
        }

        // Simpan log
        $log = AccessLog::create([
            'tags_id' => $rfidTag->tags_id,
            'accessed_at' => now(),
            'status' => $request->status,
            'note' => $request->message ?? '-',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Access log saved successfully',
            'data' => $log
        ], 201);
    }
}
