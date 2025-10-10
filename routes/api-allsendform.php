<?php

use App\Http\Controllers\Api\AllSendFormController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| AllSendForm API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for the AllSendForm module.
| These routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group.
|
*/

Route::prefix('v1/all-send-form')->group(function () {
    // AllSendForm routes
    Route::post('get-options', [AllSendFormController::class, 'getOptions']);
    Route::post('send', [AllSendFormController::class, 'send']);
    Route::post('image-upload', [AllSendFormController::class, 'imageUpload']);
    Route::post('image-delete', [AllSendFormController::class, 'imageDelete']);
});
