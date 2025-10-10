<?php

use App\Http\Controllers\Api\CreditCardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| CreditCard API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for the CreditCard module.
| These routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group.
|
*/

Route::prefix('v1/credit-card')->group(function () {
    // CreditCard routes
    Route::post('list', [CreditCardController::class, 'list']);
    Route::post('create', [CreditCardController::class, 'create']);
    Route::post('update', [CreditCardController::class, 'update']);
    Route::post('set-preferred', [CreditCardController::class, 'setPreferred']);
    Route::post('delete', [CreditCardController::class, 'delete']);
});
