<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DelayController;

Route::middleware('apikey')->prefix('v1')->group(function () {

    Route::get('/delays', [DelayController::class, 'index']);

    Route::post('/delays/notifications', [DelayController::class, 'sendNotification']);

    Route::get('/delays/{id}', [DelayController::class, 'show']);

    Route::post('/delays', [DelayController::class, 'store']);


});