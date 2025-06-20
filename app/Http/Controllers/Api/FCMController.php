<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
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
            'rfid_tag' => 'required|string',
        ]);

        $user = User::find($request->user_id);

        if (!$user || !$user->fcm_token) {
            return response()->json(['success' => false, 'message' => 'User or FCM token not found.'], 404);
        }

        $fcmToken = $user->fcm_token;
        $serviceAccountPath = storage_path('app/firebase/appsmrtgt-firebase-adminsdk-fbsvc-be0d19cf73.json');

        $client = new \Google_Client();
        $client->setAuthConfig($serviceAccountPath);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        $client->fetchAccessTokenWithAssertion();
        $accessToken = $client->getAccessToken()['access_token'];

        $projectId = 'appsmrtgt'; // Ganti dengan project ID kamu

        $message = [
            'message' => [
                'token' => $fcmToken,
                'data' => [
                    'type' => 'verification',
                    'user_id' => (string) $request->user_id,
                    'rfid_tag' => $request->rfid_tag,
                ]
            ]
        ];

        $response = Http::withToken($accessToken)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->post("https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send", $message);

        $tag = RFIDTag::where('tag_uid', $request->rfid_tag)->first();

        if ($tag) {
            AccessLog::create([
                'tags_id' => $tag->tags_id,
                'accessed_at' => now(),
                'status' => 'Pending',
                'note' => 'Menunggu verifikasi dari user.',
            ]);
        }
        
        return response()->json([
            'success' => $response->successful(),
            'status' => $response->status(),
            'body' => $response->body(),
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
