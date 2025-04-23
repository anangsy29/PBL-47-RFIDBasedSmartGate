<?php

namespace App\Http\Controllers\Api;

use App\Models\AccessLog;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
}
