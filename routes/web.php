<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\ChatController;
use App\Http\Controllers\Web\ActiveRecordLogController;
use App\Http\Controllers\Web\AdminMessageLogController;
use App\Http\Controllers\Web\ApiLogController;
use App\Http\Controllers\Web\AppleController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\BundleController;
use App\Http\Controllers\Web\ContactController;
use App\Http\Controllers\Web\ContractLineController;
use App\Http\Controllers\Web\CreditCardController;
use App\Http\Controllers\Web\DeviceController;
use App\Http\Controllers\Web\EmailPoolArchiveController;
use App\Http\Controllers\Web\EmailPoolController;
use App\Http\Controllers\Web\EmailTemplateController;
use App\Http\Controllers\Web\EmergencyTipsRequestController;
use App\Http\Controllers\Web\FollowerController;
use App\Http\Controllers\Web\FollowerListController;
use App\Http\Controllers\Web\HelpController;
use App\Http\Controllers\Web\IEXController;
use App\Http\Controllers\Web\InfluencerAssistantController;
use App\Http\Controllers\Web\InfoStateController;
use App\Http\Controllers\Web\InvoiceController;
use App\Http\Controllers\Web\LogsController;
use App\Http\Controllers\Web\MaintenanceController;
use App\Http\Controllers\Web\MoneyTransactionController;
use App\Http\Controllers\Web\NewsController;
use App\Http\Controllers\Web\PageController;
use App\Http\Controllers\Web\PhoneNumberController;
use App\Http\Controllers\Web\PodcastController;
use App\Http\Controllers\Web\ProductController;
use App\Http\Controllers\Web\PromocodeController;
use App\Http\Controllers\Web\ProviderController;
use App\Http\Controllers\Web\RegisterController;
use App\Http\Controllers\Web\ReportController;
use App\Http\Controllers\Web\SettingsController;
use App\Http\Controllers\Web\SiteController;
use App\Http\Controllers\Web\SmsPoolArchiveController;
use App\Http\Controllers\Web\SmsPoolController;
use App\Http\Controllers\Web\SmsScheduleController;
use App\Http\Controllers\Web\StaffController;
use App\Http\Controllers\Web\SubscriptionCategoryController;
use App\Http\Controllers\Web\SubscriptionController;
use App\Http\Controllers\Web\TwilioController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\UserPlanController;
use Illuminate\Support\Facades\Route;

// ========================================
// SWAGGER DOCS ROUTE (Must be before l5-swagger routes)
// ========================================
Route::get('/docs/api-docs.json', function () {
    return response()->file(storage_path('api-docs/api-docs.json'));
});

// ========================================
// WEB ROUTES (Authenticated)
// ========================================

Route::prefix('admin')->middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', fn () => view('admin.user.dashboard'))->name('admin.user.dashboard');

    // Profile Management
    Route::get('/profile', fn () => view('admin.user.profile'))->name('admin.user.profile');

    // Subscription Management
    Route::get('/subscriptions', fn () => view('admin.user.subscriptions'))->name('admin.user.subscriptions');
});

// ========================================
// WEB ROUTES (Admin Panel)
// ========================================

// Public routes (no auth required) - Admin/User unified login
Route::prefix('admin')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('site.login');
    Route::post('/login', [AuthController::class, 'submitLogin'])->name('site.login.submit');
    Route::get('/error', [SiteController::class, 'error'])->name('site.error');
});

// Protected admin routes (auth required) - All under /admin prefix
Route::prefix('admin')->middleware(['auth.any'])->group(function () {
    // Site Routes
    Route::get('/', [SiteController::class, 'index'])->name('site.index');
    Route::get('/logout', [AuthController::class, 'logout'])->name('site.logout');
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/self', [SiteController::class, 'self'])->name('site.self');
    Route::post('/self', [SiteController::class, 'selfSubmit'])->name('site.self.submit');
    Route::post('/reset', [AuthController::class, 'reset'])->name('site.reset');

    // Staff Routes
    Route::resource('staff', StaffController::class)->names([
        'index' => 'staff.index',
        'create' => 'staff.create',
        'store' => 'staff.store',
        'show' => 'staff.show',
        'edit' => 'staff.edit',
        'update' => 'staff.update',
        'destroy' => 'staff.destroy',
    ]);
    Route::post('staff/{staff}/login-as', [StaffController::class, 'loginAs'])->name('staff.login-as');

    // User Routes
    Route::get('user', [UserController::class, 'index'])->name('user.index');
    Route::get('user/create', [UserController::class, 'create'])->name('user.create');
    Route::get('user/export/csv', [UserController::class, 'toCsv'])->name('user.to-csv');
    Route::get('user/promocode', [UserController::class, 'promocode'])->name('user.promocode');
    Route::delete('user/promocode/{promocode}', [UserController::class, 'deleteSaleInfluencerPromocode'])->name('user.delete-promocode');
    Route::get('user/invoice-line/{contractLine}', [UserController::class, 'getInvoiceLine'])->name('user.invoice-line');
    Route::match(['get', 'post'], 'user/terminate/{contractLine}', [UserController::class, 'terminate'])->name('user.terminate');
    Route::post('user/set-feed-visibility', [UserController::class, 'setFeedVisibility'])->name('user.set-feed-visibility');
    Route::post('user', [UserController::class, 'store'])->name('user.store');
    Route::get('user/{user}', [UserController::class, 'show'])->name('user.show');
    Route::get('user/{user}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::put('user/{user}', [UserController::class, 'update'])->name('user.update');
    Route::delete('user/{user}', [UserController::class, 'destroy'])->name('user.destroy');
    Route::post('user/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('user.toggle-status');
    Route::post('user/{user}/login-as', [UserController::class, 'loginAsUser'])->name('user.login-as');
    Route::get('user/{user}/transaction-list', [UserController::class, 'getTransactionList'])->name('user.transaction-list');
    Route::get('user/{user}/gateway-profile', [UserController::class, 'getGatewayProfile'])->name('user.gateway-profile');
    Route::get('user/{user}/credit', [UserController::class, 'credit'])->name('user.credit');
    Route::post('user/{user}/credit', [UserController::class, 'creditStore'])->name('user.credit.store');
    Route::post('user/{user}/remove-credit', [UserController::class, 'removeCredit'])->name('user.remove-credit');
    Route::post('user/{user}/influencer-verified', [UserController::class, 'influencerVerified'])->name('user.influencer-verified');
    Route::post('user/{user}/influencer-refused', [UserController::class, 'influencerRefused'])->name('user.influencer-refused');

    // Register Routes
    Route::get('register', [RegisterController::class, 'index'])->name('register.index');
    Route::get('register/unique', [RegisterController::class, 'indexUnique'])->name('register.index-unique');
    Route::get('register/signups', [RegisterController::class, 'signups'])->name('register.signups');
    Route::get('register/export/csv', [RegisterController::class, 'toCsv'])->name('register.to-csv');
    Route::get('register/export/csv-unique', [RegisterController::class, 'toCsvUnique'])->name('register.to-csv-unique');

    // Invoice Routes
    Route::get('invoice', [InvoiceController::class, 'index'])->name('invoice.index');
    Route::get('invoice/{invoice}', [InvoiceController::class, 'show'])->name('invoice.show');

    // Money Transaction Routes
    Route::get('money-transaction', [MoneyTransactionController::class, 'index'])->name('money-transaction.index');
    Route::get('money-transaction/{transaction}', [MoneyTransactionController::class, 'show'])->name('money-transaction.show');
    Route::post('money-transaction/{transaction}/void', [MoneyTransactionController::class, 'void'])->name('money-transaction.void');
    Route::post('money-transaction/{transaction}/refund', [MoneyTransactionController::class, 'refund'])->name('money-transaction.refund');
    Route::get('money-transaction/{transaction}/details', [MoneyTransactionController::class, 'getTransactionDetails'])->name('money-transaction.details');
    Route::get('money-transaction/export/csv', [MoneyTransactionController::class, 'toCsv'])->name('money-transaction.to-csv');
    Route::get('money-transaction/report', [MoneyTransactionController::class, 'report'])->name('money-transaction.report');

    // SMS Pool Routes
    Route::get('sms-pool', [SmsPoolController::class, 'index'])->name('sms-pool.index');
    Route::get('sms-pool/{smsPool}', [SmsPoolController::class, 'show'])->name('sms-pool.show');
    Route::delete('sms-pool/{smsPool}', [SmsPoolController::class, 'destroy'])->name('sms-pool.destroy');
    Route::get('sms-pool/send/dashboard', [SmsPoolController::class, 'sendDashboard'])->name('sms-pool.send-dashboard');
    Route::post('sms-pool/send/to-all', [SmsPoolController::class, 'sendToAll'])->name('sms-pool.send-to-all');
    Route::get('sms-pool/recipient/{smsPool}', [SmsPoolController::class, 'recipient'])->name('sms-pool.recipient');
    Route::post('sms-pool/send/to-state', [SmsPoolController::class, 'sendToState'])->name('sms-pool.send-to-state');
    Route::post('sms-pool/process-send', [SmsPoolController::class, 'processSend'])->name('sms-pool.process-send');
    Route::get('sms-pool/image/{smsPool}', [SmsPoolController::class, 'getImage'])->name('sms-pool.get-image');

    // Page Routes
    Route::resource('page', PageController::class)->names([
        'index' => 'page.index',
        'create' => 'page.create',
        'store' => 'page.store',
        'show' => 'page.show',
        'edit' => 'page.edit',
        'update' => 'page.update',
        'destroy' => 'page.destroy',
    ]);
    Route::get('page/{page}/content', [PageController::class, 'content'])->name('page.content');
    Route::post('page/{page}/content', [PageController::class, 'contentSubmit'])->name('page.content.submit');
    Route::post('page/{page}/upload', [PageController::class, 'upload'])->name('page.upload');

    // Product Routes
    Route::get('product/subscription', [ProductController::class, 'subscription'])->name('product.subscription');
    Route::get('product/subscription/{service}/edit', [ProductController::class, 'editSubscription'])->name('product.edit-subscription');
    Route::post('product/subscription/{service}', [ProductController::class, 'updateSubscription'])->name('product.update-subscription');
    Route::get('product/settings', [ProductController::class, 'settings'])->name('product.settings');
    Route::post('product/settings', [ProductController::class, 'updateSettings'])->name('product.update-settings');
    Route::get('product/alert', [ProductController::class, 'alert'])->name('product.alert');
    Route::get('product/alert/{service}/edit', [ProductController::class, 'editAlert'])->name('product.edit-alert');
    Route::post('product/alert/{service}', [ProductController::class, 'updateAlert'])->name('product.update-alert');
    Route::get('product/device', [ProductController::class, 'device'])->name('product.device');
    Route::get('product/device/create', [ProductController::class, 'createDevice'])->name('product.create-device');
    Route::post('product/device', [ProductController::class, 'storeDevice'])->name('product.store-device');
    Route::get('product/device/{device}/edit', [ProductController::class, 'editDevice'])->name('product.edit-device');
    Route::post('product/device/{device}', [ProductController::class, 'updateDevice'])->name('product.update-device');
    Route::get('product/device/{device}/photos', [ProductController::class, 'photos'])->name('product.photos');
    Route::post('product/device/photo/delete', [ProductController::class, 'deletePhoto'])->name('product.delete-photo');
    Route::post('product/device/photo/set-main', [ProductController::class, 'setMainPhoto'])->name('product.set-main-photo');
    Route::post('product/device/{device}/upload', [ProductController::class, 'uploadPhoto'])->name('product.upload-photo');
    Route::get('product/plan', [ProductController::class, 'plan'])->name('product.plan');
    Route::get('product/plan/create', [ProductController::class, 'createPlan'])->name('product.create-plan');
    Route::post('product/plan', [ProductController::class, 'storePlan'])->name('product.store-plan');
    Route::get('product/plan/{plan}/edit', [ProductController::class, 'editPlan'])->name('product.edit-plan');
    Route::post('product/plan/{plan}', [ProductController::class, 'updatePlan'])->name('product.update-plan');
    Route::post('product/plan/{plan}/attach/{device}', [ProductController::class, 'attachPlan'])->name('product.attach-plan');
    Route::post('product/plan/{plan}/detach/{device}', [ProductController::class, 'detachPlan'])->name('product.detach-plan');

    // Report Routes
    Route::get('report/login-statistic', [ReportController::class, 'loginStatistic'])->name('report.login-statistic');
    Route::get('report/login-statistic/{date}', [ReportController::class, 'loginStatistic'])->name('report.login-statistic-by-day');
    Route::get('report/sold-devices', [ReportController::class, 'soldDevices'])->name('report.sold-devices');
    Route::get('report/influencer-total', [ReportController::class, 'influencerTotal'])->name('report.influencer-total');
    Route::get('report/influencer-total/{name}', [ReportController::class, 'influencerTotal'])->name('report.influencer-total-by-name');
    Route::get('report/influencer-total/{name}/csv', [ReportController::class, 'influencerTotal'])->name('report.influencer-total-csv');
    Route::get('report/referral', [ReportController::class, 'referral'])->name('report.referral');
    Route::get('report/referral/{month}', [ReportController::class, 'referral'])->name('report.referral-by-month');
    Route::get('report/referral/{month}/csv', [ReportController::class, 'refToCsv'])->name('report.ref-to-csv');
    Route::get('report/sps-termination', [ReportController::class, 'spsTermination'])->name('report.sps-termination');
    Route::get('report/statistic', [ReportController::class, 'statistic'])->name('report.statistic');
    Route::get('report/statistic/{date}', [ReportController::class, 'statistic'])->name('report.statistic-by-date');
    Route::get('report/ended', [ReportController::class, 'ended'])->name('report.ended');
    Route::get('report/ended/{name}', [ReportController::class, 'ended'])->name('report.ended-by-name');
    Route::get('report/ended/{name}/csv', [ReportController::class, 'ended'])->name('report.ended-csv');
    Route::get('report/reaction', [ReportController::class, 'reaction'])->name('report.reaction');
    Route::get('report/reaction/{name}', [ReportController::class, 'reaction'])->name('report.reaction-by-name');
    Route::get('report/devices-plans', [ReportController::class, 'devicesPlans'])->name('report.devices-plans');
    Route::get('report/devices-plans/{date}', [ReportController::class, 'devicesPlans'])->name('report.devices-plans-by-date');
    Route::get('report/sms', [ReportController::class, 'sms'])->name('report.sms');
    Route::get('report/customer-source', [ReportController::class, 'customerSource'])->name('report.customer-source');
    Route::get('report/user-point', [ReportController::class, 'userPoint'])->name('report.user-point');
    Route::get('report/user-point/{name}', [ReportController::class, 'userPoint'])->name('report.user-point-by-name');

    // Settings Routes
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('settings/flush', [SettingsController::class, 'flush'])->name('settings.flush');
    Route::post('settings/main', [SettingsController::class, 'updateMain'])->name('settings.update-main');
    Route::post('settings/sms', [SettingsController::class, 'updateSms'])->name('settings.update-sms');
    Route::post('settings/api', [SettingsController::class, 'updateApi'])->name('settings.update-api');
    Route::post('settings/app', [SettingsController::class, 'updateApp'])->name('settings.update-app');
    Route::get('settings/public', [SettingsController::class, 'public'])->name('settings.public');
    Route::post('settings/public', [SettingsController::class, 'updatePublic'])->name('settings.update-public');
    Route::post('settings/user-point', [SettingsController::class, 'updateUserPoint'])->name('settings.update-user-point');
    Route::post('settings/bitpay-generation', [SettingsController::class, 'bitpayGeneration'])->name('settings.bitpay-generation');

    // Email Template Routes
    Route::get('email-template', [EmailTemplateController::class, 'index'])->name('email-template.index');
    Route::get('email-template/{emailTemplate}', [EmailTemplateController::class, 'show'])->name('email-template.show');
    Route::get('email-template/{emailTemplate}/edit', [EmailTemplateController::class, 'edit'])->name('email-template.edit');
    Route::put('email-template/{emailTemplate}', [EmailTemplateController::class, 'update'])->name('email-template.update');
    Route::get('email-template/{emailTemplate}/view-body', [EmailTemplateController::class, 'viewBody'])->name('email-template.view-body');
    Route::get('email-template/{emailTemplate}/admin-send', [EmailTemplateController::class, 'adminSend'])->name('email-template.admin-send');
    Route::post('email-template/{emailTemplate}/send-test', [EmailTemplateController::class, 'sendTestEmail'])->name('email-template.send-test');
    Route::get('email-template/export/csv', [EmailTemplateController::class, 'export'])->name('email-template.export');

    // Help Routes
    Route::get('help', [HelpController::class, 'index'])->name('help.index');
    Route::get('help/test', [HelpController::class, 'test'])->name('help.test');
    Route::get('help/check-sps', [HelpController::class, 'checkSps'])->name('help.check-sps');
    Route::get('help/log', [HelpController::class, 'log'])->name('help.log');
    Route::get('help/download', [HelpController::class, 'download'])->name('help.download');
    Route::get('help/read-log', [HelpController::class, 'readLog'])->name('help.read-log');
    Route::get('help/test-modal', [HelpController::class, 'testModal'])->name('help.test-modal');
    Route::get('help/test-alert', [HelpController::class, 'testAlert'])->name('help.test-alert');
    Route::post('help/test-mail', [HelpController::class, 'testMail'])->name('help.test-mail');
    Route::get('help/twilio-lookup', [HelpController::class, 'twilioLookup'])->name('help.twilio-lookup');
    Route::get('help/twilio-send', [HelpController::class, 'twilioSend'])->name('help.twilio-send');
    Route::get('help/iex-test', [HelpController::class, 'iexTest'])->name('help.iex-test');
    Route::get('help/api-log-diff', [HelpController::class, 'apiLogDiff'])->name('help.api-log-diff');
    Route::get('help/sms', [HelpController::class, 'sms'])->name('help.sms');
    Route::get('help/test-push', [HelpController::class, 'testPush'])->name('help.test-push');
    Route::get('help/chat', [HelpController::class, 'chat'])->name('help.chat');

    // Apple Routes
    Route::get('apple/app-transactions', [AppleController::class, 'appTransactions'])->name('apple.app-transactions');
    Route::get('apple/app-transaction/{transaction}', [AppleController::class, 'showAppTransaction'])->name('apple.app-transaction-show');
    Route::post('apple/app-transaction/{transaction}/process', [AppleController::class, 'processTransaction'])->name('apple.process-transaction');
    Route::post('apple/app-transaction/{transaction}/retry', [AppleController::class, 'retryTransaction'])->name('apple.retry-transaction');
    Route::get('apple/app-transactions/export/csv', [AppleController::class, 'exportAppTransactions'])->name('apple.export-app-transactions');
    Route::get('apple/notifications', [AppleController::class, 'notifications'])->name('apple.notifications');
    Route::get('apple/notification/{notification}', [AppleController::class, 'showNotification'])->name('apple.notification-show');
    Route::get('apple/notifications/export/csv', [AppleController::class, 'exportNotifications'])->name('apple.export-notifications');

    // Twilio Routes
    Route::get('twilio/carriers', [TwilioController::class, 'carriers'])->name('twilio.carriers');
    Route::get('twilio/carrier/{carrier}/edit', [TwilioController::class, 'editCarrier'])->name('twilio.edit-carrier');
    Route::put('twilio/carrier/{carrier}', [TwilioController::class, 'updateCarrier'])->name('twilio.update-carrier');
    Route::get('twilio/carriers/export/csv', [TwilioController::class, 'exportCarriers'])->name('twilio.export-carriers');
    Route::get('twilio/incoming', [TwilioController::class, 'incoming'])->name('twilio.incoming');
    Route::get('twilio/incoming/create', [TwilioController::class, 'createIncoming'])->name('twilio.create-incoming');
    Route::post('twilio/incoming', [TwilioController::class, 'storeIncoming'])->name('twilio.store-incoming');
    Route::get('twilio/incoming/{incoming}', [TwilioController::class, 'showIncoming'])->name('twilio.incoming-show');
    Route::get('twilio/incoming/{incoming}/edit', [TwilioController::class, 'editIncoming'])->name('twilio.edit-incoming');
    Route::put('twilio/incoming/{incoming}', [TwilioController::class, 'updateIncoming'])->name('twilio.update-incoming');
    Route::delete('twilio/incoming/{incoming}', [TwilioController::class, 'deleteIncoming'])->name('twilio.delete-incoming');
    Route::get('twilio/incoming/export/csv', [TwilioController::class, 'exportIncoming'])->name('twilio.export-incoming');

    // IEX Routes
    Route::get('iex/webhooks', [IEXController::class, 'webhooks'])->name('iex.webhooks');
    Route::get('iex/webhook/{webhook}', [IEXController::class, 'showWebhook'])->name('iex.webhook-show');
    Route::get('iex/webhooks/export/csv', [IEXController::class, 'exportWebhooks'])->name('iex.export-webhooks');
    Route::get('iex/marketstack', [IEXController::class, 'marketstack'])->name('iex.marketstack');
    Route::get('iex/marketstack/create', [IEXController::class, 'createMarketstack'])->name('iex.create-marketstack');
    Route::post('iex/marketstack', [IEXController::class, 'storeMarketstack'])->name('iex.store-marketstack');
    Route::get('iex/marketstack/{index}', [IEXController::class, 'showMarketstack'])->name('iex.marketstack-show');
    Route::get('iex/marketstack/{index}/edit', [IEXController::class, 'editMarketstack'])->name('iex.edit-marketstack');
    Route::put('iex/marketstack/{index}', [IEXController::class, 'updateMarketstack'])->name('iex.update-marketstack');
    Route::get('iex/marketstack/export/csv', [IEXController::class, 'exportMarketstack'])->name('iex.export-marketstack');

    // Credit Card Routes
    Route::get('credit-card', [CreditCardController::class, 'index'])->name('credit-card.index');
    Route::get('credit-card/{creditCard}', [CreditCardController::class, 'show'])->name('credit-card.show');
    Route::get('credit-card/{creditCard}/get-gateway-profile', [CreditCardController::class, 'getGatewayProfile'])->name('credit-card.get-gateway-profile');
    Route::get('credit-card/export/csv', [CreditCardController::class, 'export'])->name('credit-card.export');

    // Email Pool Routes
    Route::get('email-pool', [EmailPoolController::class, 'index'])->name('email-pool.index');
    Route::get('email-pool/{emailPool}', [EmailPoolController::class, 'show'])->name('email-pool.show');
    Route::get('email-pool/{emailPool}/view-body', [EmailPoolController::class, 'viewBody'])->name('email-pool.view-body');
    Route::post('email-pool/{emailPool}/resend', [EmailPoolController::class, 'resend'])->name('email-pool.resend');
    Route::delete('email-pool/{emailPool}', [EmailPoolController::class, 'destroy'])->name('email-pool.destroy');
    Route::get('email-pool/attachment/{attachmentId}', [EmailPoolController::class, 'attachment'])->name('email-pool.attachment');
    Route::get('email-pool-archive', [EmailPoolController::class, 'archive'])->name('email-pool.archive');
    Route::get('email-pool-archive/{emailPool}', [EmailPoolController::class, 'showArchive'])->name('email-pool.archive-show');
    Route::get('email-pool/export/csv', [EmailPoolController::class, 'export'])->name('email-pool.export');

    // Podcast Routes
    Route::get('podcast', [PodcastController::class, 'index'])->name('podcast.index');
    Route::get('podcast/create', [PodcastController::class, 'create'])->name('podcast.create');
    Route::post('podcast', [PodcastController::class, 'store'])->name('podcast.store');
    Route::get('podcast/{podcast}/edit', [PodcastController::class, 'edit'])->name('podcast.edit');
    Route::put('podcast/{podcast}', [PodcastController::class, 'update'])->name('podcast.update');
    Route::delete('podcast/{podcast}', [PodcastController::class, 'destroy'])->name('podcast.destroy');
    Route::get('podcast/export/csv', [PodcastController::class, 'export'])->name('podcast.export');

    // News Routes
    Route::get('news', [NewsController::class, 'index'])->name('news.index');
    Route::get('news/create', [NewsController::class, 'create'])->name('news.create');
    Route::post('news', [NewsController::class, 'store'])->name('news.store');
    Route::get('news/{news}', [NewsController::class, 'show'])->name('news.show');
    Route::get('news/{news}/edit', [NewsController::class, 'edit'])->name('news.edit');
    Route::put('news/{news}', [NewsController::class, 'update'])->name('news.update');
    Route::delete('news/{news}', [NewsController::class, 'destroy'])->name('news.destroy');
    Route::get('news/{news}/content', [NewsController::class, 'content'])->name('news.content');

    // Phone Number Routes
    Route::get('phone-number', [PhoneNumberController::class, 'index'])->name('phone-number.index');
    Route::get('phone-number/create', [PhoneNumberController::class, 'create'])->name('phone-number.create');
    Route::post('phone-number', [PhoneNumberController::class, 'store'])->name('phone-number.store');
    Route::get('phone-number/{phoneNumber}', [PhoneNumberController::class, 'show'])->name('phone-number.show');
    Route::get('phone-number/{phoneNumber}/edit', [PhoneNumberController::class, 'edit'])->name('phone-number.edit');
    Route::put('phone-number/{phoneNumber}', [PhoneNumberController::class, 'update'])->name('phone-number.update');
    Route::delete('phone-number/{phoneNumber}', [PhoneNumberController::class, 'destroy'])->name('phone-number.destroy');
    Route::get('phone-number/{phoneNumber}/send-sms', [PhoneNumberController::class, 'sendSms'])->name('phone-number.send-sms');
    Route::post('phone-number/{phoneNumber}/send-sms', [PhoneNumberController::class, 'sendSmsPost'])->name('phone-number.send-sms-post');
    Route::get('phone-number/{phoneNumber}/super-update', [PhoneNumberController::class, 'superUpdate'])->name('phone-number.super-update');
    Route::post('phone-number/{phoneNumber}/super-update', [PhoneNumberController::class, 'superUpdatePost'])->name('phone-number.super-update-post');
    Route::get('phone-number/export/csv', [PhoneNumberController::class, 'export'])->name('phone-number.export');

    // Subscription Routes
    Route::get('subscription', [SubscriptionController::class, 'index'])->name('subscription.index');
    Route::get('subscription/create', [SubscriptionController::class, 'create'])->name('subscription.create');
    Route::post('subscription', [SubscriptionController::class, 'store'])->name('subscription.store');
    Route::get('subscription/{subscription}/edit', [SubscriptionController::class, 'edit'])->name('subscription.edit');
    Route::put('subscription/{subscription}', [SubscriptionController::class, 'update'])->name('subscription.update');
    Route::get('subscription/{subscription}/super-update', [SubscriptionController::class, 'superUpdate'])->name('subscription.super-update');
    Route::post('subscription/{subscription}/activate', [SubscriptionController::class, 'activate'])->name('subscription.activate');
    Route::post('subscription/{subscription}/deactivate', [SubscriptionController::class, 'deactivate'])->name('subscription.deactivate');
    Route::delete('subscription/{subscription}', [SubscriptionController::class, 'destroy'])->name('subscription.destroy');
    Route::get('subscription/export/csv', [SubscriptionController::class, 'export'])->name('subscription.export');

    // Promocode Routes
    Route::get('promocode', [PromocodeController::class, 'index'])->name('promocode.index');
    Route::get('promocode/create', [PromocodeController::class, 'create'])->name('promocode.create');
    Route::post('promocode', [PromocodeController::class, 'store'])->name('promocode.store');
    Route::get('promocode/{promocode}/edit', [PromocodeController::class, 'edit'])->name('promocode.edit');
    Route::put('promocode/{promocode}', [PromocodeController::class, 'update'])->name('promocode.update');
    Route::delete('promocode/{promocode}', [PromocodeController::class, 'destroy'])->name('promocode.destroy');
    Route::get('promocode/export/csv', [PromocodeController::class, 'export'])->name('promocode.export');

    // Logs Routes
    Route::get('logs/active-record-logs', [LogsController::class, 'activeRecordLogs'])->name('logs.active-record-logs');
    Route::get('logs/api-logs', [LogsController::class, 'apiLogs'])->name('logs.api-logs');
    Route::get('logs/api-logs/{apiLog}', [LogsController::class, 'showApiLog'])->name('logs.show-api-log');
    Route::post('logs/api-logs/delete-by-key/{key}', [LogsController::class, 'deleteByKey'])->name('logs.delete-by-key');
    Route::get('logs/active-record-logs/export/csv', [LogsController::class, 'exportActiveRecordLogs'])->name('logs.export-active-record-logs');
    Route::get('logs/api-logs/export/csv', [LogsController::class, 'exportApiLogs'])->name('logs.export-api-logs');

    // Maintenance Routes
    Route::get('maintenance', [MaintenanceController::class, 'index'])->name('maintenance.index');
    Route::post('maintenance/set-session', [MaintenanceController::class, 'setSession'])->name('maintenance.set-session');
    Route::get('maintenance/clear-cache', [MaintenanceController::class, 'clearCache'])->name('maintenance.clear-cache');
    Route::get('maintenance/clear-logs', [MaintenanceController::class, 'clearLogs'])->name('maintenance.clear-logs');
    Route::get('maintenance/database-maintenance', [MaintenanceController::class, 'databaseMaintenance'])->name('maintenance.database-maintenance');
    Route::get('maintenance/system-info', [MaintenanceController::class, 'systemInfo'])->name('maintenance.system-info');
    Route::get('maintenance/queue-status', [MaintenanceController::class, 'queueStatus'])->name('maintenance.queue-status');
    Route::get('maintenance/storage-status', [MaintenanceController::class, 'storageStatus'])->name('maintenance.storage-status');

    // Device Routes
    Route::get('device', [DeviceController::class, 'index'])->name('device.index');
    Route::get('device/{device}', [DeviceController::class, 'show'])->name('device.show');
    Route::delete('device/{device}', [DeviceController::class, 'destroy'])->name('device.destroy');
    Route::get('device/{device}/push', [DeviceController::class, 'push'])->name('device.push');
    Route::post('device/{device}/send-push', [DeviceController::class, 'sendPush'])->name('device.send-push');
    Route::get('device/export/csv', [DeviceController::class, 'export'])->name('device.export');

    // Provider Routes
    Route::get('provider', [ProviderController::class, 'index'])->name('provider.index');
    Route::get('provider/create', [ProviderController::class, 'create'])->name('provider.create');
    Route::post('provider', [ProviderController::class, 'store'])->name('provider.store');
    Route::get('provider/{provider}', [ProviderController::class, 'show'])->name('provider.show');
    Route::get('provider/{provider}/edit', [ProviderController::class, 'edit'])->name('provider.edit');
    Route::put('provider/{provider}', [ProviderController::class, 'update'])->name('provider.update');
    Route::delete('provider/{provider}', [ProviderController::class, 'destroy'])->name('provider.destroy');
    Route::get('provider/export/csv', [ProviderController::class, 'export'])->name('provider.export');

    // User Plan Routes
    Route::get('user-plan/unpaid', [UserPlanController::class, 'unpaid'])->name('user-plan.unpaid');
    Route::get('user-plan/{userPlan}/edit', [UserPlanController::class, 'edit'])->name('user-plan.edit');
    Route::put('user-plan/{userPlan}', [UserPlanController::class, 'update'])->name('user-plan.update');
    Route::get('user-plan/unpaid/export/csv', [UserPlanController::class, 'exportUnpaid'])->name('user-plan.export-unpaid');

    // Subscription Category Routes
    Route::get('subscription-category', [SubscriptionCategoryController::class, 'index'])->name('subscription-category.index');
    Route::get('subscription-category/{subscriptionCategory}', [SubscriptionCategoryController::class, 'show'])->name('subscription-category.show');
    Route::get('subscription-category/{subscriptionCategory}/edit', [SubscriptionCategoryController::class, 'edit'])->name('subscription-category.edit');
    Route::put('subscription-category/{subscriptionCategory}', [SubscriptionCategoryController::class, 'update'])->name('subscription-category.update');
    Route::get('subscription-category/export/csv', [SubscriptionCategoryController::class, 'export'])->name('subscription-category.export');

    // Follower Routes
    Route::get('follower', [FollowerController::class, 'index'])->name('follower.index');
    Route::get('follower/export/csv', [FollowerController::class, 'export'])->name('follower.export');

    // Follower List Routes
    Route::get('follower-list', [FollowerListController::class, 'index'])->name('follower-list.index');
    Route::get('follower-list/{followerList}', [FollowerListController::class, 'show'])->name('follower-list.show');
    Route::get('follower-list/export/csv', [FollowerListController::class, 'export'])->name('follower-list.export');

    // Influencer Assistant Routes
    Route::get('influencer-assistant', [InfluencerAssistantController::class, 'index'])->name('influencer-assistant.index');
    Route::get('influencer-assistant/create', [InfluencerAssistantController::class, 'create'])->name('influencer-assistant.create');
    Route::post('influencer-assistant', [InfluencerAssistantController::class, 'store'])->name('influencer-assistant.store');
    Route::delete('influencer-assistant/{idInfluencer}/{idAssistant}', [InfluencerAssistantController::class, 'destroy'])->name('influencer-assistant.destroy');
    Route::get('influencer-assistant/export/csv', [InfluencerAssistantController::class, 'export'])->name('influencer-assistant.export');

    // Info State Routes
    Route::get('info-state', [InfoStateController::class, 'index'])->name('info-state.index');
    Route::get('info-state/{infoState}', [InfoStateController::class, 'show'])->name('info-state.show');
    Route::delete('info-state/{infoState}', [InfoStateController::class, 'destroy'])->name('info-state.destroy');
    Route::get('info-state/export/csv', [InfoStateController::class, 'export'])->name('info-state.export');

    // Emergency Tips Request Routes
    Route::get('emergency-tips-request', [EmergencyTipsRequestController::class, 'index'])->name('emergency-tips-request.index');
    Route::get('emergency-tips-request/{emergencyTipsRequest}', [EmergencyTipsRequestController::class, 'show'])->name('emergency-tips-request.show');
    Route::get('emergency-tips-request/export/csv', [EmergencyTipsRequestController::class, 'export'])->name('emergency-tips-request.export');

    // SMS Schedule Routes
    Route::get('sms-schedule', [SmsScheduleController::class, 'index'])->name('sms-schedule.index');
    Route::get('sms-schedule/{smsSchedule}', [SmsScheduleController::class, 'show'])->name('sms-schedule.show');
    Route::get('sms-schedule/export/csv', [SmsScheduleController::class, 'export'])->name('sms-schedule.export');

    // SMS Pool Archive Routes
    Route::get('sms-pool-archive', [SmsPoolArchiveController::class, 'index'])->name('sms-pool-archive.index');
    Route::get('sms-pool-archive/{smsPoolArchive}', [SmsPoolArchiveController::class, 'show'])->name('sms-pool-archive.show');
    Route::get('sms-pool-archive/export/csv', [SmsPoolArchiveController::class, 'export'])->name('sms-pool-archive.export');

    // Email Pool Archive Routes
    Route::get('email-pool-archive', [EmailPoolArchiveController::class, 'index'])->name('email-pool-archive.index');
    Route::get('email-pool-archive/{emailPoolArchive}', [EmailPoolArchiveController::class, 'show'])->name('email-pool-archive.show');
    Route::get('email-pool-archive/{emailPoolArchive}/view-body', [EmailPoolArchiveController::class, 'viewBody'])->name('email-pool-archive.view-body');
    Route::get('email-pool-archive/attachment/{attachment}', [EmailPoolArchiveController::class, 'attachment'])->name('email-pool-archive.attachment');
    Route::delete('email-pool-archive/{emailPoolArchive}', [EmailPoolArchiveController::class, 'destroy'])->name('email-pool-archive.destroy');
    Route::get('email-pool-archive/export/csv', [EmailPoolArchiveController::class, 'export'])->name('email-pool-archive.export');

    // API Log Routes
    Route::get('api-log', [ApiLogController::class, 'index'])->name('api-log.index');
    Route::get('api-log/{apiLog}', [ApiLogController::class, 'show'])->name('api-log.show');
    Route::delete('api-log/{apiLog}/delete-by-key', [ApiLogController::class, 'deleteByKey'])->name('api-log.delete-by-key');
    Route::get('api-log/export/csv', [ApiLogController::class, 'export'])->name('api-log.export');

    // Active Record Log Routes
    Route::get('active-record-log', [ActiveRecordLogController::class, 'index'])->name('active-record-log.index');
    Route::get('active-record-log/export/csv', [ActiveRecordLogController::class, 'export'])->name('active-record-log.export');

    // Admin Message Log Routes
    Route::get('admin-message-log', [AdminMessageLogController::class, 'index'])->name('admin-message-log.index');
    Route::get('admin-message-log/export/csv', [AdminMessageLogController::class, 'export'])->name('admin-message-log.export');

    // Contract Line Routes
    Route::get('contract-line/unpaid', [ContractLineController::class, 'unpaid'])->name('contract-line.unpaid');
    Route::get('contract-line/unpaid/export/csv', [ContractLineController::class, 'exportUnpaid'])->name('contract-line.export-unpaid');

    // Bundle Routes
    Route::get('bundle', [BundleController::class, 'index'])->name('bundle.index');
    Route::get('bundle/create', [BundleController::class, 'create'])->name('bundle.create');
    Route::post('bundle', [BundleController::class, 'store'])->name('bundle.store');
    Route::get('bundle/{bundle}', [BundleController::class, 'show'])->name('bundle.show');
    Route::get('bundle/{bundle}/edit', [BundleController::class, 'edit'])->name('bundle.edit');
    Route::put('bundle/{bundle}', [BundleController::class, 'update'])->name('bundle.update');
    Route::delete('bundle/{bundle}', [BundleController::class, 'destroy'])->name('bundle.destroy');
    Route::get('bundle/export/csv', [BundleController::class, 'export'])->name('bundle.export');

    // Contact Routes
    Route::get('contact', [ContactController::class, 'index'])->name('contact.index');
    Route::get('contact/create', [ContactController::class, 'create'])->name('contact.create');
    Route::post('contact', [ContactController::class, 'store'])->name('contact.store');
    Route::get('contact/{contact}', [ContactController::class, 'show'])->name('contact.show');
    Route::get('contact/{contact}/edit', [ContactController::class, 'edit'])->name('contact.edit');
    Route::put('contact/{contact}', [ContactController::class, 'update'])->name('contact.update');
    Route::delete('contact/{contact}', [ContactController::class, 'destroy'])->name('contact.destroy');
    Route::get('contact/export/csv', [ContactController::class, 'export'])->name('contact.export');
    Route::post('contact/{contact}/toggle-status', [ContactController::class, 'toggleStatus'])->name('contact.toggle-status');
    Route::post('contact/{contact}/mark-replied', [ContactController::class, 'markAsReplied'])->name('contact.mark-replied');

    // Chat Routes
    Route::get('chat', [ChatController::class, 'index'])->name('chat.admin');
    Route::get('chat/messages/{conversationId}', [ChatController::class, 'getMessages']);
    Route::get('chat/recent-messages', [ChatController::class, 'getRecentMessages']);
    Route::post('chat/send', [ChatController::class, 'sendMessage']);

    // News Routes
    Route::get('news', [NewsController::class, 'index'])->name('news.index');
    Route::get('news/create', [NewsController::class, 'create'])->name('news.create');
    Route::post('news', [NewsController::class, 'store'])->name('news.store');
    Route::get('news/{news}', [NewsController::class, 'show'])->name('news.show');
    Route::get('news/{news}/edit', [NewsController::class, 'edit'])->name('news.edit');
    Route::put('news/{news}', [NewsController::class, 'update'])->name('news.update');
    Route::delete('news/{news}', [NewsController::class, 'destroy'])->name('news.destroy');
    Route::get('news/export/csv', [NewsController::class, 'export'])->name('news.export');
});

// ========================================
// VUE SPA CATCH-ALL (exclude admin and api)
// ========================================
Route::view('/{any}', 'app')->where('any', '^(?!admin|api|docs)(.*)$');
