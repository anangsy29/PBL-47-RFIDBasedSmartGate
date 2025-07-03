<?php

namespace App\Jobs;

use App\Models\AccessLog;
use App\Models\RFIDTag;
use App\Models\User;
use Google_Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendFCMNotificationJob implements ShouldQueue
// {
//     use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

//     protected $userId;
//     protected $tagUid;

//     public function __construct($userId, $tagUid)
//     {
//         $this->userId = $userId;
//         $this->tagUid = $tagUid;
//     }

//     public function handle(): void
//     {
//         Log::info("📡 Queue FCM: user_id={$this->userId}, tag_uid={$this->tagUid}");

//         $user = User::find($this->userId);
//         if (!$user || !$user->fcm_token) {
//             Log::warning("⚠️ User tidak ditemukan atau token FCM kosong");
//             return;
//         }

//         $client = new Google_Client();
//         $client->setAuthConfig(storage_path('app/firebase/appsmrtgt-firebase-adminsdk-fbsvc-be0d19cf73.json'));
//         $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
//         $client->fetchAccessTokenWithAssertion();
//         $accessToken = $client->getAccessToken()['access_token'] ?? null;

//         if (!$accessToken) {
//             Log::error("❌ Gagal mendapatkan access token Firebase");
//             return;
//         }

//         $message = [
//             'message' => [
//                 'token' => $user->fcm_token,
//                 'data' => [
//                     'type' => 'verification',
//                     'user_id' => (string) $this->userId,
//                     'rfid_tag' => $this->tagUid,
//                 ],
//             ],
//         ];

//         $response = Http::withToken($accessToken)
//             ->withHeaders(['Content-Type' => 'application/json'])
//             ->post("https://fcm.googleapis.com/v1/projects/appsmrtgt/messages:send", $message);

//         Log::info("✅ FCM Response:", ['status' => $response->status(), 'body' => $response->body()]);

//         $tag = RFIDTag::where('tag_uid', $this->tagUid)->first();
//         if ($tag) {
//             AccessLog::create([
//                 'tags_id' => $tag->tags_id,
//                 'accessed_at' => now(),
//                 'status' => 'Pending',
//                 'note' => 'Menunggu verifikasi dari user.',
//             ]);
//         }
//     }
// }
