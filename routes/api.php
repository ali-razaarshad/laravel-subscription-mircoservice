<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\SubscriptionController;
use Illuminate\Support\Facades\Route;


// All routes protected by Sanctum tokens
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/subscriptions', [SubscriptionController::class, 'index']); // admin only (Gate in controller)
    Route::post('/subscribe', [SubscriptionController::class, 'subscribe']);
    Route::get('/user/subscriptions', [SubscriptionController::class, 'myActive']);
});
