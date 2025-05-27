<?php

use App\Http\Controllers\Api\WahaWebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/waha/webhook', [WahaWebhookController::class, 'handle']);
