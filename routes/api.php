<?php

declare(strict_types=1);

use App\Http\Controllers\Api\AllSendFormController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookmarkController;
use App\Http\Controllers\Api\BundleController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\ContractLineController;
use App\Http\Controllers\Api\CreditCardController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\DeviceController;
use App\Http\Controllers\Api\EmailPoolController;
use App\Http\Controllers\Api\EmailTemplateController;
use App\Http\Controllers\Api\InfluencerController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\MaintenanceController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PhoneController;
use App\Http\Controllers\Api\PrivateFeedController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\PublicFeedController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\SettingsController;
use App\Http\Controllers\Api\StaffController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\UsersController;
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

Route::middleware('auth:sanctum')->get('/user', fn (Request $request) => $request->user());

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
    Route::get('list', [InfluencerController::class, 'listInfluencers']);
});

// Routes from api-legacy.php
Route::post('auth/login', [AuthController::class, 'webLogin'])->name('api.login');

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

    // Users routes (admin only)
    Route::get('users', [UsersController::class, 'index']);
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

// Posts CRUD API routes - reusing existing methods
Route::prefix('v1/posts')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [NewsController::class, 'last']); // Reuse existing last method
    Route::post('/', [NewsController::class, 'create']); // Reuse existing create method
    Route::get('{id}', [NewsController::class, 'getPost']);
    Route::put('{id}', [NewsController::class, 'updatePost']);
    Route::delete('{id}', [NewsController::class, 'deletePost']);
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

// ===== NEWLY MIGRATED CONTROLLERS =====

// Bundle Controller - Service management
Route::prefix('v1/bundle')->middleware('auth:sanctum')->group(function () {
    Route::get('index', [BundleController::class, 'index']);
    Route::post('create', [BundleController::class, 'create']);
    Route::put('update/{id}', [BundleController::class, 'update']);
    Route::post('device-data', [BundleController::class, 'deviceData']);
});

// Contact Controller - Customer support
Route::prefix('v1/contact')->middleware('auth:sanctum')->group(function () {
    Route::get('index', [ContactController::class, 'index']);
    Route::get('view/{id}', [ContactController::class, 'show']);
    Route::delete('delete/{id}', [ContactController::class, 'delete']);
});

// ContractLine Controller - Financial contracts
Route::prefix('v1/contract-line')->middleware('auth:sanctum')->group(function () {
    Route::get('index', [ContractLineController::class, 'index']);
    Route::get('view/{id}', [ContractLineController::class, 'show']);
    Route::post('create', [ContractLineController::class, 'create']);
    Route::put('update/{id}', [ContractLineController::class, 'update']);
    Route::delete('delete/{id}', [ContractLineController::class, 'delete']);
    Route::get('unpaid', [ContractLineController::class, 'unpaid']);
});

// EmailPool Controller - Email queue
Route::prefix('v1/email-pool')->middleware('auth:sanctum')->group(function () {
    Route::get('index', [EmailPoolController::class, 'index']);
    Route::get('view/{id}', [EmailPoolController::class, 'show']);
    Route::post('create', [EmailPoolController::class, 'create']);
    Route::put('update/{id}', [EmailPoolController::class, 'update']);
    Route::delete('delete/{id}', [EmailPoolController::class, 'delete']);
});

// EmailTemplate Controller - Email templates
Route::prefix('v1/email-template')->middleware('auth:sanctum')->group(function () {
    Route::get('index', [EmailTemplateController::class, 'index']);
    Route::get('view/{id}', [EmailTemplateController::class, 'show']);
    Route::put('update/{id}', [EmailTemplateController::class, 'update']);
    Route::get('body/{id}', [EmailTemplateController::class, 'getBody']);
    Route::post('send/{id}', [EmailTemplateController::class, 'sendAdminEmail']);
});

// Report Controller - Business intelligence
Route::prefix('v1/report')->middleware('auth:sanctum')->group(function () {
    Route::get('login-statistic', [ReportController::class, 'loginStatistic']);
    Route::get('sold-devices', [ReportController::class, 'soldDevices']);
    Route::get('influencer-total', [ReportController::class, 'influencerTotal']);
    Route::get('referral', [ReportController::class, 'referral']);
    Route::get('statistic', [ReportController::class, 'statistic']);
    Route::get('sms', [ReportController::class, 'sms']);
});

// Settings Controller - System configuration
Route::prefix('v1/settings')->middleware('auth:sanctum')->group(function () {
    Route::get('index', [SettingsController::class, 'index']);
    Route::put('update', [SettingsController::class, 'update']);
    Route::post('flush', [SettingsController::class, 'flush']);
    Route::get('public', [SettingsController::class, 'public']);
    Route::post('bitpay-generation', [SettingsController::class, 'bitpayGeneration']);
});

// Staff Controller - Admin management
Route::prefix('v1/staff')->middleware('auth:sanctum')->group(function () {
    Route::get('index', [StaffController::class, 'index']);
    Route::get('view/{id}', [StaffController::class, 'view']);
    Route::post('create', [StaffController::class, 'create']);
    Route::put('update/{id}', [StaffController::class, 'update']);
    Route::delete('delete/{id}', [StaffController::class, 'delete']);
});

// Invoice Controller - Financial invoices
Route::prefix('v1/invoice')->middleware('auth:sanctum')->group(function () {
    Route::get('index', [InvoiceController::class, 'index']);
    Route::get('view/{id}', [InvoiceController::class, 'view']);
});

// Bookmark Controller - Bookmark management
Route::prefix('v1/bookmark')->middleware('auth:sanctum')->group(function () {
    Route::get('list', [BookmarkController::class, 'list']);
    Route::post('create', [BookmarkController::class, 'create']);
    Route::delete('delete/{id}', [BookmarkController::class, 'delete']);
});

// Maintenance Controller - System maintenance
Route::prefix('v1/maintenance')->middleware('auth:sanctum')->group(function () {
    Route::post('summarize', [MaintenanceController::class, 'summarize']);
});
