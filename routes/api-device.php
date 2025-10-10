<?php

use App\Http\Controllers\Api\DeviceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Device API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for the Device module.
| These routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group.
|
*/

Route::prefix('v1/device')->group(function () {
    // Device routes
    Route::post('update', [DeviceController::class, 'update']);
    Route::post('verify', [DeviceController::class, 'verify']);
    Route::get('push-settings-get', [DeviceController::class, 'pushSettingsGet']);
    Route::post('push-settings-set', [DeviceController::class, 'pushSettingsSet']);
});
