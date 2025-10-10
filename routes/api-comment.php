<?php

use App\Http\Controllers\Api\CommentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Comment API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for the Comment module.
| These routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group.
|
*/

Route::prefix('v1/comment')->group(function () {
    // Comment routes
    Route::post('get', [CommentController::class, 'get']);
    Route::post('get-replies', [CommentController::class, 'getReplies']);
    Route::post('create', [CommentController::class, 'create']);
    Route::post('update', [CommentController::class, 'update']);
    Route::post('delete', [CommentController::class, 'delete']);
});
