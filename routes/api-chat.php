<?php

use App\Http\Controllers\Api\ChatController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Chat API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for the Chat module.
| These routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group.
|
*/

Route::prefix('v1/chat')->group(function () {
    // Chat routes
    Route::post('list', [ChatController::class, 'list']);
    Route::post('chat-messages', [ChatController::class, 'chatMessages']);
    Route::post('view', [ChatController::class, 'view']);
    Route::post('upload', [ChatController::class, 'upload']);
    Route::post('create-group', [ChatController::class, 'createGroup']);
    Route::post('get-friend-chat', [ChatController::class, 'getFriendChat']);
    Route::get('get-file/{id}/{type?}', [ChatController::class, 'getFile']);
});
