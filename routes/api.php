<?php

use App\Http\Controllers\Api\AllSendFormController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookmarkController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\CreditCardController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\DeviceController;
use App\Http\Controllers\Api\InfluencerController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PhoneController;
use App\Http\Controllers\Api\PrivateFeedController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\PublicFeedController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\SubscriptionController;
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

// Routes from api-allsendform.php
Route::prefix('v1/all-send-form')->middleware('auth:sanctum')->group(function () {
    // AllSendForm routes
    Route::post('get-options', [AllSendFormController::class, 'getOptions']);
    Route::post('send', [AllSendFormController::class, 'send']);
    Route::post('image-upload', [AllSendFormController::class, 'imageUpload']);
    Route::post('image-delete', [AllSendFormController::class, 'imageDelete']);
});

// Routes from api-auth.php
Route::prefix('v1/auth')->group(function () {
    // Authentication routes
    Route::post('login', [AuthController::class, 'login']);
    Route::get('test', [AuthController::class, 'test']);
    Route::options('{any}', [AuthController::class, 'options'])->where('any', '.*');
});

// Routes from api-chat.php
Route::prefix('v1/chat')->middleware('auth:sanctum')->group(function () {
    // Chat routes
    Route::post('list', [ChatController::class, 'list']);
    Route::post('chat-messages', [ChatController::class, 'chatMessages']);
    Route::post('view', [ChatController::class, 'view']);
    Route::post('upload', [ChatController::class, 'upload']);
    Route::post('create-group', [ChatController::class, 'createGroup']);
    Route::post('get-friend-chat', [ChatController::class, 'getFriendChat']);
    Route::get('get-file/{id}/{type?}', [ChatController::class, 'getFile']);
});

// Routes from api-comment.php
Route::prefix('v1/comment')->middleware('auth:sanctum')->group(function () {
    // Comment routes
    Route::post('get', [CommentController::class, 'get']);
    Route::post('get-replies', [CommentController::class, 'getReplies']);
    Route::post('create', [CommentController::class, 'create']);
    Route::post('update', [CommentController::class, 'update']);
    Route::post('delete', [CommentController::class, 'delete']);
});

// Routes from api-creditcard.php
Route::prefix('v1/credit-card')->middleware('auth:sanctum')->group(function () {
    // CreditCard routes
    Route::post('list', [CreditCardController::class, 'list']);
    Route::post('create', [CreditCardController::class, 'create']);
    Route::post('update', [CreditCardController::class, 'update']);
    Route::post('set-preferred', [CreditCardController::class, 'setPreferred']);
    Route::post('delete', [CreditCardController::class, 'delete']);
});

// Routes from api-customer.php
Route::prefix('v1/customer')->middleware('auth:sanctum')->group(function () {
    // Customer routes
    Route::get('get-profile', [CustomerController::class, 'getProfile']);
    Route::post('update-profile', [CustomerController::class, 'updateProfile']);
    Route::post('change-password', [CustomerController::class, 'changePassword']);
    Route::post('update-privacy', [CustomerController::class, 'updatePrivacy']);
    Route::post('remove-avatar', [CustomerController::class, 'removeAvatar']);
    Route::post('remove-header', [CustomerController::class, 'removeHeader']);
    Route::post('logout', [CustomerController::class, 'logout']);
    Route::post('delete', [CustomerController::class, 'delete']);
});

// Routes from api-default.php
Route::prefix('v1/default')->group(function () {
    // Authentication routes
    Route::post('login', [AuthController::class, 'login']);
    Route::post('signup', [AuthController::class, 'signup']);
    Route::post('news-by-subscription', [AuthController::class, 'newsBySubscription']);
    Route::options('{any}', [AuthController::class, 'options'])->where('any', '.*');
});

// Routes from api-device.php
Route::prefix('v1/device')->middleware('auth:sanctum')->group(function () {
    // Device routes
    Route::post('update', [DeviceController::class, 'update']);
    Route::post('verify', [DeviceController::class, 'verify']);
    Route::get('push-settings-get', [DeviceController::class, 'pushSettingsGet']);
    Route::post('push-settings-set', [DeviceController::class, 'pushSettingsSet']);
});

// Routes from api-influencer.php
Route::prefix('v1/influencer')->middleware('auth:sanctum')->group(function () {
    // Influencer routes
    Route::get('index', [InfluencerController::class, 'index']);
    Route::post('create', [InfluencerController::class, 'create']);
});

// Routes from api-legacy.php
Route::post('auth/login', [App\Http\Controllers\Api\AuthController::class, 'webLogin'])->name('api.login');

Route::middleware('auth:sanctum')->group(function () {
    // News routes
    Route::post('news/last', [NewsController::class, 'last']);
    Route::post('news/get-latest-news', [NewsController::class, 'getLatestNews']);
    Route::post('news/by-influencers', [NewsController::class, 'byInfluencers']);
    Route::post('news/by-subscription', [NewsController::class, 'bySubscription']);
    Route::post('news/by-follower-list', [NewsController::class, 'byFollowerList']);
    Route::post('news/set-reaction', [NewsController::class, 'setReaction']);
    Route::post('news/remove-reaction', [NewsController::class, 'removeReaction']);
    Route::post('news/report', [NewsController::class, 'report']);
    Route::post('news/mute', [NewsController::class, 'mute']);
    Route::post('news/unmute', [NewsController::class, 'unmute']);
    Route::post('news/create', [NewsController::class, 'create']);

    // Influencer routes
    Route::get('influencer/index', [InfluencerController::class, 'index']);
    Route::post('influencer/create', [InfluencerController::class, 'create']);

    // Private feed routes
    Route::get('private-feed/index', [PrivateFeedController::class, 'index']);
    Route::post('private-feed/create', [PrivateFeedController::class, 'create']);
    Route::post('private-feed/update', [PrivateFeedController::class, 'update']);
    Route::post('private-feed/delete', [PrivateFeedController::class, 'delete']);

    // Feed/Subscription routes
    Route::post('feed/index', [SubscriptionController::class, 'index']);
    Route::post('feed/set', [SubscriptionController::class, 'set']);
    Route::post('feed/satellite-set', [SubscriptionController::class, 'satelliteSet']);
    Route::get('feed/category', [SubscriptionController::class, 'category']);
    Route::get('feed/satellite-index', [SubscriptionController::class, 'satelliteIndex']);
    Route::post('feed/add-own-marketstack-indx-subscription', [SubscriptionController::class, 'addOwnMarketstackIndxSubscription']);
    Route::post('feed/add-own-marketstack-ticker-subscription', [SubscriptionController::class, 'addOwnMarketstackTickerSubscription']);
    Route::get('feed/options', [SubscriptionController::class, 'options']);
    Route::post('feed/delete-private-feed', [SubscriptionController::class, 'deletePrivateFeed']);
    Route::get('feed/get-image', [SubscriptionController::class, 'getImage']);

    // Comment routes
    Route::post('comment/get', [CommentController::class, 'get']);
    Route::post('comment/get-replies', [CommentController::class, 'getReplies']);
    Route::post('comment/create', [CommentController::class, 'create']);
    Route::post('comment/update', [CommentController::class, 'update']);
    Route::post('comment/delete', [CommentController::class, 'delete']);

    // Search routes
    Route::post('search/index', [SearchController::class, 'index']);

    // Bookmark routes
    Route::post('bookmark/index', [BookmarkController::class, 'index']);
    Route::post('bookmark/create', [BookmarkController::class, 'create']);
    Route::delete('bookmark/delete', [BookmarkController::class, 'delete']);
});

// Routes from api-news.php
Route::prefix('v1/news')->middleware('auth:sanctum')->group(function () {
    // News routes
    Route::post('last', [NewsController::class, 'last']);
    Route::post('get-latest-news', [NewsController::class, 'getLatestNews']);
    Route::post('by-influencers', [NewsController::class, 'byInfluencers']);
    Route::post('by-subscription', [NewsController::class, 'bySubscription']);
    Route::post('by-follower-list', [NewsController::class, 'byFollowerList']);
    Route::post('set-reaction', [NewsController::class, 'setReaction']);
    Route::post('remove-reaction', [NewsController::class, 'removeReaction']);
    Route::post('report', [NewsController::class, 'report']);
    Route::post('mute', [NewsController::class, 'mute']);
    Route::post('unmute', [NewsController::class, 'unmute']);
});

// Routes from api-order.php
Route::prefix('v1/order')->middleware('auth:sanctum')->group(function () {
    // Order routes
    Route::post('create', [OrderController::class, 'create']);
    Route::post('pay', [OrderController::class, 'pay']);
    Route::get('test', [OrderController::class, 'test']);
});

// Routes from api-phone.php
Route::prefix('v1/phone')->middleware('auth:sanctum')->group(function () {
    // Phone routes
    Route::post('verify', [PhoneController::class, 'verify']);
    Route::post('set', [PhoneController::class, 'set']);
});

// Routes from api-privatefeed.php
Route::prefix('v1/private-feed')->middleware('auth:sanctum')->group(function () {
    // PrivateFeed routes
    Route::get('index', [PrivateFeedController::class, 'index']);
    Route::post('create', [PrivateFeedController::class, 'create']);
    Route::post('update', [PrivateFeedController::class, 'update']);
    Route::post('delete', [PrivateFeedController::class, 'delete']);
});

// Routes from api-product.php
Route::prefix('v1/product')->middleware('auth:sanctum')->group(function () {
    // Product routes
    Route::post('list', [ProductController::class, 'list']);
    Route::post('alerts', [ProductController::class, 'alerts']);
    Route::post('purchase', [ProductController::class, 'purchase']);
});

// Routes from api-publicfeed.php
Route::prefix('v1/public-feed')->middleware('auth:sanctum')->group(function () {
    // PublicFeed routes
    Route::post('get-options', [PublicFeedController::class, 'getOptions']);
    Route::post('send', [PublicFeedController::class, 'send']);
    Route::post('image-upload', [PublicFeedController::class, 'imageUpload']);
});

// Routes from api-subscription.php
Route::prefix('v1/feed')->middleware('auth:sanctum')->group(function () {
    // Subscription routes
    Route::post('set', [SubscriptionController::class, 'set']);
    Route::post('satellite-set', [SubscriptionController::class, 'satelliteSet']);
    Route::get('category', [SubscriptionController::class, 'category']);
    Route::post('index', [SubscriptionController::class, 'index']);
    Route::get('satellite-index', [SubscriptionController::class, 'satelliteIndex']);
    Route::post('add-own-marketstack-indx-subscription', [SubscriptionController::class, 'addOwnMarketstackIndxSubscription']);
    Route::post('add-own-marketstack-ticker-subscription', [SubscriptionController::class, 'addOwnMarketstackTickerSubscription']);
    Route::get('options', [SubscriptionController::class, 'options']);
    Route::post('delete-private-feed', [SubscriptionController::class, 'deletePrivateFeed']);
    Route::get('get-image', [SubscriptionController::class, 'getImage']);
});
