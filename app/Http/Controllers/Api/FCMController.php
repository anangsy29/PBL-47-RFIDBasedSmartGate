<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
// use App\Jobs\SendFCMNotificationJob;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AccessLog;
use App\Models\Vehicle;
use App\Models\RFIDTag;
use Google_Client;
use Illuminate\Support\Facades\Storage;
// use Google\Auth\Credentials\ServiceAccountCredentials;

class FCMController extends Controller
{
    public function saveToken(Request $request)
    {
        \Log::info('Request masuk:', $request->all());

        $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'fcm_token' => 'required|string',
        ]);

        $user = User::find($request->input('user_id'));
        $user->fcm_token = $request->input('fcm_token');
        $user->save();

        \Log::info("✅ FCM token disimpan untuk user ID {$user->user_id}: {$user->fcm_token}");

        return response()->json([
            'success' => true,
            'message' => 'FCM token berhasil disimpan.',
        ]);
    }

    public function sendVerificationNotification(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'tag_uid' => 'required|string', // ✅ gunakan tag_uid, bukan rfid_tag
        ]);

        $user = User::where('user_id', $request->user_id)->first();

        if (!$user || !$user->fcm_token) {
            return response()->json(['success' => false, 'message' => 'User or FCM token not found.'], 404);
        }

        $fcmToken = $user->fcm_token;
        $projectId = 'appsmrtgt'; // ✅ Ganti dengan project Firebase kamu
        $serviceAccountPath = storage_path('app/firebase/appsmrtgt-firebase-adminsdk-fbsvc-be0d19cf73.json');

        // 🔐 Ambil akses token FCM
        $client = new \Google_Client();
        $client->setAuthConfig($serviceAccountPath);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        $client->fetchAccessTokenWithAssertion();

        $accessToken = $client->getAccessToken()['access_token'] ?? null;

        if (!$accessToken) {
            return response()->json(['success' => false, 'message' => 'Gagal ambil akses token FCM.']);
        }

        // 📩 Data yang dikirim ke aplikasi mobile
        $message = [
            'message' => [
                'token' => $fcmToken,
                'data' => [
                    'type' => 'verification',
                    'user_id' => (string) $request->user_id,
                    'tag_uid' => $request->tag_uid, // ✅ konsisten pakai tag_uid
                ]
            ]
        ];

        // 🔄 Simpan ke log access (status pending)
        $tag = RFIDTag::where('tag_uid', $request->tag_uid)->first();

        if ($tag) {
            AccessLog::create([
                'tags_id' => $tag->tags_id,
                'accessed_at' => now(),
                'status' => 'Pending',
                'note' => 'Menunggu verifikasi dari user.',
            ]);
        }

        // 📨 Kirim notifikasi ke FCM
        $response = Http::withToken($accessToken)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->post("https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send", $message);

        return response()->json([
            'success' => $response->successful(),
            'status' => $response->status(),
            'body' => $response->body(),
        ]);
    }

    public function handleVerificationResponse(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'tag_uid' => 'required|string',  // ✅ konsisten pakai tag_uid
            'response' => 'required|in:yes,no',
        ]);

        $tag = RFIDtag::where('tag_uid', $request->tag_uid)->first();

        if (!$tag) {
            return response()->json(['success' => false, 'message' => 'Tag not found.'], 404);
        }

        // Update Access Log terbaru yang masih Pending
        $accessLog = AccessLog::where('tags_id', $tag->tags_id)
            ->where('status', 'Pending')
            ->latest('accessed_at')
            ->first();

        if ($accessLog) {
            $accessLog->status = $request->response === 'yes' ? 'Approved' : 'Denied';
            $accessLog->note = 'Verifikasi user: ' . strtoupper($request->response);
            $accessLog->save();
        }

        // Kirim sinyal ke Python listener jika disetujui
        if ($request->response === 'yes') {
            try {
                Http::post('http://192.168.1.200:8000/open-gate', [  // ✅ gunakan localhost jika Python di local
                    'tag_uid' => $request->tag_uid,
                    'user_id' => $request->user_id,
                    'action' => 'open',
                ]);
            } catch (\Exception $e) {
                // log atau abaikan jika Python server belum siap
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Response has been recorded.',
        ]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'old_password' => 'required',
            'new_password' => 'required|min:6',
        ]);

        $user = User::find($request->user_id);

        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json(['success' => false, 'message' => 'Password lama salah.'], 403);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['success' => true, 'message' => 'Password berhasil diubah.']);
    }

    //logs
    public function getAccessLogs($userId)
    {
        // Ambil semua vehicles_id yang dimiliki user
        $vehicleIds = Vehicle::where('user_id', $userId)->pluck('vehicles_id');

        // Ambil semua tags_id yang terkait dengan vehicle tersebut
        $tagIds = RFIDTag::whereIn('vehicles_id', $vehicleIds)->pluck('tags_id');

        // Ambil semua log berdasarkan tag_id
        $logs = AccessLog::whereIn('tags_id', $tagIds)
            ->orderBy('accessed_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'logs' => $logs,
        ]);
    }
}

    // public function sendVerificationNotificationV1($userId)
    // {
    //     $user = User::find($userId);
    //     if (!$user || !$user->fcm_token) {
    //         return response()->json(['error' => 'User atau token tidak ditemukan'], 404);
    //     }

    //     $client = new Client();
    //     $client->setAuthConfig(storage_path('app/firebase/appsmrtgt-firebase-adminsdk-fbsvc-be0d19cf73.json'));
    //     $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
    //     $client->fetchAccessTokenWithAssertion();

    //     $accessToken = $client->getAccessToken()['access_token'];

    //     $projectId = env('FIREBASE_PROJECT_ID');

    //     $response = Http::withToken($accessToken)
    //         ->withHeaders([
    //             'Content-Type' => 'application/json',
    //         ])
    //         ->post("https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send", [
    //             'message' => [
    //                 'token' => $user->fcm_token,
    //                 'data' => [
    //                     'type' => 'verification',
    //                     'user_id' => (string)$user->id
    //                 ],
    //                 'notification' => [
    //                     'title' => 'Permintaan Akses',
    //                     'body' => 'Apakah Anda ingin membuka gate?'
    //                 ]
    //             ]
    //         ]);

    //     if ($response->successful()) {
    //         \Log::info("✅ Notifikasi verifikasi terkirim ke user {$userId}");
    //         return response()->json(['message' => 'Notifikasi dikirim']);
    //     } else {
    //         \Log::error("❌ Gagal kirim notifikasi: " . $response->body());
    //         return response()->json(['error' => 'Gagal kirim notifikasi'], 500);
    //     }
    // }

    // public function tagDetected(Request $request)
    // {
    //     $userId = $request->input('user_id');

    //     $fcmController = new FCMController();
    //     return $fcmController->sendVerificationNotificationV1($userId);
    // }

    // public function openGate(Request $request)
    // {
    //     $request->validate([
    //         'user_id' => 'required|exists:users,id',
    //     ]);

    //     // Contoh logika buka gate
    //     $userId = $request->input('user_id');
    //     // TODO: Kirim ke ESP8266 via MQTT atau HTTP

    //     return response()->json([
    //         'success' => true,
    //         'message' => "Gate dibuka untuk user ID: $userId"
    //     ]);
    // }
