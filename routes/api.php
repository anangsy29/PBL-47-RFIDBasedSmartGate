<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AccessLogController;
use App\Http\Controllers\Api\ValidateController;

// login
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Api\AuthController;
use App\Models\AccessLog;

// FCM
use App\Http\Controllers\Api\FCMController;


// use App\Http\Controllers\Api\NotificationController;

Route::post('/log-access', [AccessLogController::class, 'store']);

Route::fallback(function () {
    return response()->json(['message' => 'API route not found.'], 404);
});

Route::get('/cek', fn() => 'Hello from API route!');

// login mobile
Route::post('/login', [AuthController::class, 'login']);
// End login

// FCM Controller
// Token
Route::post('/save-fcm-token', [FCMController::class, 'saveToken']);
// SendVerification
Route::post('/send-verification', [FCMController::class, 'sendVerificationNotification']);
// Password
Route::post('/change-password', [FCMController::class, 'updatePassword']);
// log
Route::get('/access-logs/{userId}', [FCMController::class, 'getAccessLogs']);

// Validate
Route::get('/validate-tag', [ValidateController::class, 'validateTag']);
Route::fallback(function () {
    return response()->json(['message' => 'API route not found.'], 404);
});

Route::post('/verify-response', [FCMController::class, 'handleVerificationResponse']);

// Store-output
Route::post('/store-output', [AccessLogController::class, 'storeOutput']);

// FCM gate
// Route::post('/open-gate', [FCMController::class, 'openGate']);
// Route::post('/send-verification/{user_id}', [FCMController::class, 'sendVerificationNotificationV1']);
// FCM End

// Notif
// Route::post('/send-notification', [NotificationController::class, 'sendNotification']);