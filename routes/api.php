<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AccessLogController;

Route::post('/log-access', [AccessLogController::class, 'store']);

Route::fallback(function () {
    return response()->json(['message' => 'API route not found.'], 404);
});

Route::get('/cek', fn() => 'Hello from API route!');
