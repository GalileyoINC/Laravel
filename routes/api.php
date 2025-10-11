<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group.
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Include module-specific routes
require __DIR__.'/api-auth.php';
require __DIR__.'/api-default.php';
require __DIR__.'/api-chat.php';
require __DIR__.'/api-comment.php';
require __DIR__.'/api-creditcard.php';
require __DIR__.'/api-customer.php';
require __DIR__.'/api-device.php';
require __DIR__.'/api-influencer.php';
require __DIR__.'/api-news.php';
require __DIR__.'/api-order.php';
require __DIR__.'/api-phone.php';
require __DIR__.'/api-privatefeed.php';
require __DIR__.'/api-product.php';
require __DIR__.'/api-publicfeed.php';
require __DIR__.'/api-subscription.php';
require __DIR__.'/api-allsendform.php';
