<?php

declare(strict_types=1);

use App\Http\Controllers\Web\ActiveRecordLogController;
use App\Http\Controllers\Web\AdminMessageLogController;
use App\Http\Controllers\Web\ApiLogController;
use App\Http\Controllers\Web\AppleController;
use App\Http\Controllers\Web\ContractLineController;
use App\Http\Controllers\Web\CreditCardController;
use App\Http\Controllers\Web\DemoController;
use App\Http\Controllers\Web\DeviceController;
use App\Http\Controllers\Web\EmailPoolArchiveController;
use App\Http\Controllers\Web\EmailPoolController;
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
use App\Http\Controllers\Web\PageController;
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
use App\Http\Controllers\Web\UserPlanController;
use Illuminate\Support\Facades\Route;

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

// Public routes (no auth required)
Route::prefix('web')->group(function () {
    // Login routes (public)
    Route::get('/login', [SiteController::class, 'login'])->name('web.site.login');
    Route::post('/login', [SiteController::class, 'loginSubmit'])->name('web.site.login.submit');
    Route::get('/error', [SiteController::class, 'error'])->name('web.site.error');
});

// Protected routes (auth required)
Route::prefix('web')->middleware(['auth', 'verified'])->group(function () {
    // Site Routes
    Route::get('/', [SiteController::class, 'index'])->name('web.site.index');
    Route::post('/logout', [SiteController::class, 'logout'])->name('web.site.logout');
    Route::get('/self', [SiteController::class, 'self'])->name('web.site.self');
    Route::post('/self', [SiteController::class, 'selfSubmit'])->name('web.site.self.submit');
    Route::post('/reset', [SiteController::class, 'reset'])->name('web.site.reset');

    // Staff Routes
    Route::resource('staff', StaffController::class);
    Route::post('staff/{staff}/login-as', [StaffController::class, 'loginAs'])->name('web.staff.login-as');

    // Register Routes
    Route::get('register', [RegisterController::class, 'index'])->name('web.register.index');
    Route::get('register/unique', [RegisterController::class, 'indexUnique'])->name('web.register.index-unique');
    Route::get('register/signups', [RegisterController::class, 'signups'])->name('web.register.signups');
    Route::get('register/export/csv', [RegisterController::class, 'toCsv'])->name('web.register.to-csv');
    Route::get('register/export/csv-unique', [RegisterController::class, 'toCsvUnique'])->name('web.register.to-csv-unique');

    // Invoice Routes
    Route::get('invoice', [InvoiceController::class, 'index'])->name('web.invoice.index');
    Route::get('invoice/{invoice}', [InvoiceController::class, 'show'])->name('web.invoice.show');

    // Money Transaction Routes
    Route::get('money-transaction', [MoneyTransactionController::class, 'index'])->name('web.money-transaction.index');
    Route::get('money-transaction/{transaction}', [MoneyTransactionController::class, 'show'])->name('web.money-transaction.show');
    Route::post('money-transaction/{transaction}/void', [MoneyTransactionController::class, 'void'])->name('web.money-transaction.void');
    Route::post('money-transaction/{transaction}/refund', [MoneyTransactionController::class, 'refund'])->name('web.money-transaction.refund');
    Route::get('money-transaction/{transaction}/details', [MoneyTransactionController::class, 'getTransactionDetails'])->name('web.money-transaction.details');
    Route::get('money-transaction/export/csv', [MoneyTransactionController::class, 'toCsv'])->name('web.money-transaction.to-csv');
    Route::get('money-transaction/report', [MoneyTransactionController::class, 'report'])->name('web.money-transaction.report');

    // SMS Pool Routes
    Route::get('sms-pool', [SmsPoolController::class, 'index'])->name('web.sms-pool.index');
    Route::get('sms-pool/{smsPool}', [SmsPoolController::class, 'show'])->name('web.sms-pool.show');
    Route::delete('sms-pool/{smsPool}', [SmsPoolController::class, 'destroy'])->name('web.sms-pool.destroy');
    Route::get('sms-pool/send/dashboard', [SmsPoolController::class, 'sendDashboard'])->name('web.sms-pool.send-dashboard');
    Route::post('sms-pool/send/to-all', [SmsPoolController::class, 'sendToAll'])->name('web.sms-pool.send-to-all');
    Route::get('sms-pool/recipient/{smsPool}', [SmsPoolController::class, 'recipient'])->name('web.sms-pool.recipient');
    Route::post('sms-pool/send/to-state', [SmsPoolController::class, 'sendToState'])->name('web.sms-pool.send-to-state');
    Route::get('sms-pool/image/{smsPool}', [SmsPoolController::class, 'getImage'])->name('web.sms-pool.get-image');

    // Page Routes
    Route::resource('page', PageController::class);
    Route::get('page/{page}/content', [PageController::class, 'content'])->name('web.page.content');
    Route::post('page/{page}/content', [PageController::class, 'contentSubmit'])->name('web.page.content.submit');
    Route::post('page/{page}/upload', [PageController::class, 'upload'])->name('web.page.upload');

    // Product Routes
    Route::get('product/subscription', [ProductController::class, 'subscription'])->name('web.product.subscription');
    Route::get('product/subscription/{service}/edit', [ProductController::class, 'editSubscription'])->name('web.product.edit-subscription');
    Route::post('product/subscription/{service}', [ProductController::class, 'updateSubscription'])->name('web.product.update-subscription');
    Route::get('product/settings', [ProductController::class, 'settings'])->name('web.product.settings');
    Route::post('product/settings', [ProductController::class, 'updateSettings'])->name('web.product.update-settings');
    Route::get('product/alert', [ProductController::class, 'alert'])->name('web.product.alert');
    Route::get('product/alert/{service}/edit', [ProductController::class, 'editAlert'])->name('web.product.edit-alert');
    Route::post('product/alert/{service}', [ProductController::class, 'updateAlert'])->name('web.product.update-alert');
    Route::get('product/device', [ProductController::class, 'device'])->name('web.product.device');
    Route::get('product/device/create', [ProductController::class, 'createDevice'])->name('web.product.create-device');
    Route::post('product/device', [ProductController::class, 'storeDevice'])->name('web.product.store-device');
    Route::get('product/device/{device}/edit', [ProductController::class, 'editDevice'])->name('web.product.edit-device');
    Route::post('product/device/{device}', [ProductController::class, 'updateDevice'])->name('web.product.update-device');
    Route::get('product/device/{device}/photos', [ProductController::class, 'photos'])->name('web.product.photos');
    Route::post('product/device/photo/delete', [ProductController::class, 'deletePhoto'])->name('web.product.delete-photo');
    Route::post('product/device/photo/set-main', [ProductController::class, 'setMainPhoto'])->name('web.product.set-main-photo');
    Route::post('product/device/{device}/upload', [ProductController::class, 'uploadPhoto'])->name('web.product.upload-photo');
    Route::get('product/plan', [ProductController::class, 'plan'])->name('web.product.plan');
    Route::get('product/plan/create', [ProductController::class, 'createPlan'])->name('web.product.create-plan');
    Route::post('product/plan', [ProductController::class, 'storePlan'])->name('web.product.store-plan');
    Route::get('product/plan/{plan}/edit', [ProductController::class, 'editPlan'])->name('web.product.edit-plan');
    Route::post('product/plan/{plan}', [ProductController::class, 'updatePlan'])->name('web.product.update-plan');
    Route::post('product/plan/{plan}/attach/{device}', [ProductController::class, 'attachPlan'])->name('web.product.attach-plan');
    Route::post('product/plan/{plan}/detach/{device}', [ProductController::class, 'detachPlan'])->name('web.product.detach-plan');

    // Report Routes
    Route::get('report/login-statistic', [ReportController::class, 'loginStatistic'])->name('web.report.login-statistic');
    Route::get('report/login-statistic/{date}', [ReportController::class, 'loginStatistic'])->name('web.report.login-statistic-by-day');
    Route::get('report/sold-devices', [ReportController::class, 'soldDevices'])->name('web.report.sold-devices');
    Route::get('report/influencer-total', [ReportController::class, 'influencerTotal'])->name('web.report.influencer-total');
    Route::get('report/influencer-total/{name}', [ReportController::class, 'influencerTotal'])->name('web.report.influencer-total-by-name');
    Route::get('report/influencer-total/{name}/csv', [ReportController::class, 'influencerTotal'])->name('web.report.influencer-total-csv');
    Route::get('report/referral', [ReportController::class, 'referral'])->name('web.report.referral');
    Route::get('report/referral/{month}', [ReportController::class, 'referral'])->name('web.report.referral-by-month');
    Route::get('report/referral/{month}/csv', [ReportController::class, 'refToCsv'])->name('web.report.ref-to-csv');
    Route::get('report/sps-termination', [ReportController::class, 'spsTermination'])->name('web.report.sps-termination');
    Route::get('report/statistic', [ReportController::class, 'statistic'])->name('web.report.statistic');
    Route::get('report/statistic/{date}', [ReportController::class, 'statistic'])->name('web.report.statistic-by-date');
    Route::get('report/ended', [ReportController::class, 'ended'])->name('web.report.ended');
    Route::get('report/ended/{name}', [ReportController::class, 'ended'])->name('web.report.ended-by-name');
    Route::get('report/ended/{name}/csv', [ReportController::class, 'ended'])->name('web.report.ended-csv');
    Route::get('report/reaction', [ReportController::class, 'reaction'])->name('web.report.reaction');
    Route::get('report/reaction/{name}', [ReportController::class, 'reaction'])->name('web.report.reaction-by-name');
    Route::get('report/devices-plans', [ReportController::class, 'devicesPlans'])->name('web.report.devices-plans');
    Route::get('report/devices-plans/{date}', [ReportController::class, 'devicesPlans'])->name('web.report.devices-plans-by-date');
    Route::get('report/sms', [ReportController::class, 'sms'])->name('web.report.sms');
    Route::get('report/customer-source', [ReportController::class, 'customerSource'])->name('web.report.customer-source');
    Route::get('report/user-point', [ReportController::class, 'userPoint'])->name('web.report.user-point');
    Route::get('report/user-point/{name}', [ReportController::class, 'userPoint'])->name('web.report.user-point-by-name');

    // Settings Routes
    Route::get('settings', [SettingsController::class, 'index'])->name('web.settings.index');
    Route::post('settings/flush', [SettingsController::class, 'flush'])->name('web.settings.flush');
    Route::post('settings/main', [SettingsController::class, 'updateMain'])->name('web.settings.update-main');
    Route::post('settings/sms', [SettingsController::class, 'updateSms'])->name('web.settings.update-sms');
    Route::post('settings/api', [SettingsController::class, 'updateApi'])->name('web.settings.update-api');
    Route::post('settings/app', [SettingsController::class, 'updateApp'])->name('web.settings.update-app');
    Route::get('settings/public', [SettingsController::class, 'public'])->name('web.settings.public');
    Route::post('settings/public', [SettingsController::class, 'updatePublic'])->name('web.settings.update-public');
    Route::post('settings/user-point', [SettingsController::class, 'updateUserPoint'])->name('web.settings.update-user-point');
    Route::post('settings/bitpay-generation', [SettingsController::class, 'bitpayGeneration'])->name('web.settings.bitpay-generation');

    // Help Routes
    Route::get('help', [HelpController::class, 'index'])->name('web.help.index');
    Route::get('help/test', [HelpController::class, 'test'])->name('web.help.test');
    Route::get('help/check-sps', [HelpController::class, 'checkSps'])->name('web.help.check-sps');
    Route::get('help/log', [HelpController::class, 'log'])->name('web.help.log');
    Route::get('help/download', [HelpController::class, 'download'])->name('web.help.download');
    Route::get('help/read-log', [HelpController::class, 'readLog'])->name('web.help.read-log');
    Route::get('help/test-modal', [HelpController::class, 'testModal'])->name('web.help.test-modal');
    Route::get('help/test-alert', [HelpController::class, 'testAlert'])->name('web.help.test-alert');
    Route::post('help/test-mail', [HelpController::class, 'testMail'])->name('web.help.test-mail');
    Route::get('help/twilio-lookup', [HelpController::class, 'twilioLookup'])->name('web.help.twilio-lookup');
    Route::get('help/twilio-send', [HelpController::class, 'twilioSend'])->name('web.help.twilio-send');
    Route::get('help/iex-test', [HelpController::class, 'iexTest'])->name('web.help.iex-test');
    Route::get('help/api-log-diff', [HelpController::class, 'apiLogDiff'])->name('web.help.api-log-diff');
    Route::get('help/sms', [HelpController::class, 'sms'])->name('web.help.sms');
    Route::get('help/test-push', [HelpController::class, 'testPush'])->name('web.help.test-push');
    Route::get('help/chat', [HelpController::class, 'chat'])->name('web.help.chat');

    // Apple Routes
    Route::get('apple/app-transactions', [AppleController::class, 'appTransactions'])->name('web.apple.app-transactions');
    Route::get('apple/app-transaction/{transaction}', [AppleController::class, 'showAppTransaction'])->name('web.apple.app-transaction-show');
    Route::post('apple/app-transaction/{transaction}/process', [AppleController::class, 'processTransaction'])->name('web.apple.process-transaction');
    Route::post('apple/app-transaction/{transaction}/retry', [AppleController::class, 'retryTransaction'])->name('web.apple.retry-transaction');
    Route::get('apple/app-transactions/export/csv', [AppleController::class, 'exportAppTransactions'])->name('web.apple.export-app-transactions');
    Route::get('apple/notifications', [AppleController::class, 'notifications'])->name('web.apple.notifications');
    Route::get('apple/notification/{notification}', [AppleController::class, 'showNotification'])->name('web.apple.notification-show');
    Route::get('apple/notifications/export/csv', [AppleController::class, 'exportNotifications'])->name('web.apple.export-notifications');

    // Twilio Routes
    Route::get('twilio/carriers', [TwilioController::class, 'carriers'])->name('web.twilio.carriers');
    Route::get('twilio/carrier/{carrier}/edit', [TwilioController::class, 'editCarrier'])->name('web.twilio.edit-carrier');
    Route::put('twilio/carrier/{carrier}', [TwilioController::class, 'updateCarrier'])->name('web.twilio.update-carrier');
    Route::get('twilio/carriers/export/csv', [TwilioController::class, 'exportCarriers'])->name('web.twilio.export-carriers');
    Route::get('twilio/incoming', [TwilioController::class, 'incoming'])->name('web.twilio.incoming');
    Route::get('twilio/incoming/create', [TwilioController::class, 'createIncoming'])->name('web.twilio.create-incoming');
    Route::post('twilio/incoming', [TwilioController::class, 'storeIncoming'])->name('web.twilio.store-incoming');
    Route::get('twilio/incoming/{incoming}', [TwilioController::class, 'showIncoming'])->name('web.twilio.incoming-show');
    Route::get('twilio/incoming/{incoming}/edit', [TwilioController::class, 'editIncoming'])->name('web.twilio.edit-incoming');
    Route::put('twilio/incoming/{incoming}', [TwilioController::class, 'updateIncoming'])->name('web.twilio.update-incoming');
    Route::delete('twilio/incoming/{incoming}', [TwilioController::class, 'deleteIncoming'])->name('web.twilio.delete-incoming');
    Route::get('twilio/incoming/export/csv', [TwilioController::class, 'exportIncoming'])->name('web.twilio.export-incoming');

    // IEX Routes
    Route::get('iex/webhooks', [IEXController::class, 'webhooks'])->name('web.iex.webhooks');
    Route::get('iex/webhook/{webhook}', [IEXController::class, 'showWebhook'])->name('web.iex.webhook-show');
    Route::get('iex/webhooks/export/csv', [IEXController::class, 'exportWebhooks'])->name('web.iex.export-webhooks');
    Route::get('iex/marketstack', [IEXController::class, 'marketstack'])->name('web.iex.marketstack');
    Route::get('iex/marketstack/create', [IEXController::class, 'createMarketstack'])->name('web.iex.create-marketstack');
    Route::post('iex/marketstack', [IEXController::class, 'storeMarketstack'])->name('web.iex.store-marketstack');
    Route::get('iex/marketstack/{index}', [IEXController::class, 'showMarketstack'])->name('web.iex.marketstack-show');
    Route::get('iex/marketstack/{index}/edit', [IEXController::class, 'editMarketstack'])->name('web.iex.edit-marketstack');
    Route::put('iex/marketstack/{index}', [IEXController::class, 'updateMarketstack'])->name('web.iex.update-marketstack');
    Route::get('iex/marketstack/export/csv', [IEXController::class, 'exportMarketstack'])->name('web.iex.export-marketstack');

    // Credit Card Routes
    Route::get('credit-card', [CreditCardController::class, 'index'])->name('web.credit-card.index');
    Route::get('credit-card/{creditCard}', [CreditCardController::class, 'show'])->name('web.credit-card.show');
    Route::get('credit-card/{creditCard}/get-gateway-profile', [CreditCardController::class, 'getGatewayProfile'])->name('web.credit-card.get-gateway-profile');
    Route::get('credit-card/export/csv', [CreditCardController::class, 'export'])->name('web.credit-card.export');

    // Email Pool Routes
    Route::get('email-pool', [EmailPoolController::class, 'index'])->name('web.email-pool.index');
    Route::get('email-pool/{emailPool}', [EmailPoolController::class, 'show'])->name('web.email-pool.show');
    Route::get('email-pool/{emailPool}/view-body', [EmailPoolController::class, 'viewBody'])->name('web.email-pool.view-body');
    Route::post('email-pool/{emailPool}/resend', [EmailPoolController::class, 'resend'])->name('web.email-pool.resend');
    Route::delete('email-pool/{emailPool}', [EmailPoolController::class, 'destroy'])->name('web.email-pool.destroy');
    Route::get('email-pool/attachment/{attachmentId}', [EmailPoolController::class, 'attachment'])->name('web.email-pool.attachment');
    Route::get('email-pool-archive', [EmailPoolController::class, 'archive'])->name('web.email-pool.archive');
    Route::get('email-pool-archive/{emailPool}', [EmailPoolController::class, 'showArchive'])->name('web.email-pool.archive-show');
    Route::get('email-pool/export/csv', [EmailPoolController::class, 'export'])->name('web.email-pool.export');

    // Podcast Routes
    Route::get('podcast', [PodcastController::class, 'index'])->name('web.podcast.index');
    Route::get('podcast/create', [PodcastController::class, 'create'])->name('web.podcast.create');
    Route::post('podcast', [PodcastController::class, 'store'])->name('web.podcast.store');
    Route::get('podcast/{podcast}/edit', [PodcastController::class, 'edit'])->name('web.podcast.edit');
    Route::put('podcast/{podcast}', [PodcastController::class, 'update'])->name('web.podcast.update');
    Route::delete('podcast/{podcast}', [PodcastController::class, 'destroy'])->name('web.podcast.destroy');
    Route::get('podcast/export/csv', [PodcastController::class, 'export'])->name('web.podcast.export');

    // Subscription Routes
    Route::get('subscription', [SubscriptionController::class, 'index'])->name('web.subscription.index');
    Route::get('subscription/create', [SubscriptionController::class, 'create'])->name('web.subscription.create');
    Route::post('subscription', [SubscriptionController::class, 'store'])->name('web.subscription.store');
    Route::get('subscription/{subscription}/edit', [SubscriptionController::class, 'edit'])->name('web.subscription.edit');
    Route::put('subscription/{subscription}', [SubscriptionController::class, 'update'])->name('web.subscription.update');
    Route::get('subscription/{subscription}/super-update', [SubscriptionController::class, 'superUpdate'])->name('web.subscription.super-update');
    Route::post('subscription/{subscription}/activate', [SubscriptionController::class, 'activate'])->name('web.subscription.activate');
    Route::post('subscription/{subscription}/deactivate', [SubscriptionController::class, 'deactivate'])->name('web.subscription.deactivate');
    Route::delete('subscription/{subscription}', [SubscriptionController::class, 'destroy'])->name('web.subscription.destroy');
    Route::get('subscription/export/csv', [SubscriptionController::class, 'export'])->name('web.subscription.export');

    // Promocode Routes
    Route::get('promocode', [PromocodeController::class, 'index'])->name('web.promocode.index');
    Route::get('promocode/create', [PromocodeController::class, 'create'])->name('web.promocode.create');
    Route::post('promocode', [PromocodeController::class, 'store'])->name('web.promocode.store');
    Route::get('promocode/{promocode}/edit', [PromocodeController::class, 'edit'])->name('web.promocode.edit');
    Route::put('promocode/{promocode}', [PromocodeController::class, 'update'])->name('web.promocode.update');
    Route::delete('promocode/{promocode}', [PromocodeController::class, 'destroy'])->name('web.promocode.destroy');
    Route::get('promocode/export/csv', [PromocodeController::class, 'export'])->name('web.promocode.export');

    // Logs Routes
    Route::get('logs/active-record-logs', [LogsController::class, 'activeRecordLogs'])->name('web.logs.active-record-logs');
    Route::get('logs/api-logs', [LogsController::class, 'apiLogs'])->name('web.logs.api-logs');
    Route::get('logs/api-logs/{apiLog}', [LogsController::class, 'showApiLog'])->name('web.logs.show-api-log');
    Route::post('logs/api-logs/delete-by-key/{key}', [LogsController::class, 'deleteByKey'])->name('web.logs.delete-by-key');
    Route::get('logs/active-record-logs/export/csv', [LogsController::class, 'exportActiveRecordLogs'])->name('web.logs.export-active-record-logs');
    Route::get('logs/api-logs/export/csv', [LogsController::class, 'exportApiLogs'])->name('web.logs.export-api-logs');

    // Maintenance Routes
    Route::get('maintenance', [MaintenanceController::class, 'index'])->name('web.maintenance.index');
    Route::post('maintenance/set-session', [MaintenanceController::class, 'setSession'])->name('web.maintenance.set-session');
    Route::get('maintenance/clear-cache', [MaintenanceController::class, 'clearCache'])->name('web.maintenance.clear-cache');
    Route::get('maintenance/clear-logs', [MaintenanceController::class, 'clearLogs'])->name('web.maintenance.clear-logs');
    Route::get('maintenance/database-maintenance', [MaintenanceController::class, 'databaseMaintenance'])->name('web.maintenance.database-maintenance');
    Route::get('maintenance/system-info', [MaintenanceController::class, 'systemInfo'])->name('web.maintenance.system-info');
    Route::get('maintenance/queue-status', [MaintenanceController::class, 'queueStatus'])->name('web.maintenance.queue-status');
    Route::get('maintenance/storage-status', [MaintenanceController::class, 'storageStatus'])->name('web.maintenance.storage-status');

    // Demo Routes
    Route::get('demo/bootstrap-wysiwyg', [DemoController::class, 'bootstrapWysiwyg'])->name('web.demo.bootstrap-wysiwyg');
    Route::get('demo/grape', [DemoController::class, 'grape'])->name('web.demo.grape');
    Route::get('demo/grape2', [DemoController::class, 'grape2'])->name('web.demo.grape2');

    // Device Routes
    Route::get('device', [DeviceController::class, 'index'])->name('web.device.index');
    Route::get('device/{device}', [DeviceController::class, 'show'])->name('web.device.show');
    Route::delete('device/{device}', [DeviceController::class, 'destroy'])->name('web.device.destroy');
    Route::get('device/{device}/push', [DeviceController::class, 'push'])->name('web.device.push');
    Route::post('device/{device}/send-push', [DeviceController::class, 'sendPush'])->name('web.device.send-push');
    Route::get('device/export/csv', [DeviceController::class, 'export'])->name('web.device.export');

    // Provider Routes
    Route::get('provider', [ProviderController::class, 'index'])->name('web.provider.index');
    Route::get('provider/create', [ProviderController::class, 'create'])->name('web.provider.create');
    Route::post('provider', [ProviderController::class, 'store'])->name('web.provider.store');
    Route::get('provider/{provider}', [ProviderController::class, 'show'])->name('web.provider.show');
    Route::get('provider/{provider}/edit', [ProviderController::class, 'edit'])->name('web.provider.edit');
    Route::put('provider/{provider}', [ProviderController::class, 'update'])->name('web.provider.update');
    Route::delete('provider/{provider}', [ProviderController::class, 'destroy'])->name('web.provider.destroy');
    Route::get('provider/export/csv', [ProviderController::class, 'export'])->name('web.provider.export');

    // User Plan Routes
    Route::get('user-plan/unpaid', [UserPlanController::class, 'unpaid'])->name('web.user-plan.unpaid');
    Route::get('user-plan/{userPlan}/edit', [UserPlanController::class, 'edit'])->name('web.user-plan.edit');
    Route::put('user-plan/{userPlan}', [UserPlanController::class, 'update'])->name('web.user-plan.update');
    Route::get('user-plan/unpaid/export/csv', [UserPlanController::class, 'exportUnpaid'])->name('web.user-plan.export-unpaid');

    // Subscription Category Routes
    Route::get('subscription-category', [SubscriptionCategoryController::class, 'index'])->name('web.subscription-category.index');
    Route::get('subscription-category/{subscriptionCategory}', [SubscriptionCategoryController::class, 'show'])->name('web.subscription-category.show');
    Route::get('subscription-category/{subscriptionCategory}/edit', [SubscriptionCategoryController::class, 'edit'])->name('web.subscription-category.edit');
    Route::put('subscription-category/{subscriptionCategory}', [SubscriptionCategoryController::class, 'update'])->name('web.subscription-category.update');
    Route::get('subscription-category/export/csv', [SubscriptionCategoryController::class, 'export'])->name('web.subscription-category.export');

    // Follower Routes
    Route::get('follower', [FollowerController::class, 'index'])->name('web.follower.index');
    Route::get('follower/export/csv', [FollowerController::class, 'export'])->name('web.follower.export');

    // Follower List Routes
    Route::get('follower-list', [FollowerListController::class, 'index'])->name('web.follower-list.index');
    Route::get('follower-list/{followerList}', [FollowerListController::class, 'show'])->name('web.follower-list.show');
    Route::get('follower-list/export/csv', [FollowerListController::class, 'export'])->name('web.follower-list.export');

    // Influencer Assistant Routes
    Route::get('influencer-assistant', [InfluencerAssistantController::class, 'index'])->name('web.influencer-assistant.index');
    Route::get('influencer-assistant/create', [InfluencerAssistantController::class, 'create'])->name('web.influencer-assistant.create');
    Route::post('influencer-assistant', [InfluencerAssistantController::class, 'store'])->name('web.influencer-assistant.store');
    Route::delete('influencer-assistant/{idInfluencer}/{idAssistant}', [InfluencerAssistantController::class, 'destroy'])->name('web.influencer-assistant.destroy');
    Route::get('influencer-assistant/export/csv', [InfluencerAssistantController::class, 'export'])->name('web.influencer-assistant.export');

    // Info State Routes
    Route::get('info-state', [InfoStateController::class, 'index'])->name('web.info-state.index');
    Route::get('info-state/{infoState}', [InfoStateController::class, 'show'])->name('web.info-state.show');
    Route::delete('info-state/{infoState}', [InfoStateController::class, 'destroy'])->name('web.info-state.destroy');
    Route::get('info-state/export/csv', [InfoStateController::class, 'export'])->name('web.info-state.export');

    // Emergency Tips Request Routes
    Route::get('emergency-tips-request', [EmergencyTipsRequestController::class, 'index'])->name('web.emergency-tips-request.index');
    Route::get('emergency-tips-request/{emergencyTipsRequest}', [EmergencyTipsRequestController::class, 'show'])->name('web.emergency-tips-request.show');
    Route::get('emergency-tips-request/export/csv', [EmergencyTipsRequestController::class, 'export'])->name('web.emergency-tips-request.export');

    // SMS Schedule Routes
    Route::get('sms-schedule', [SmsScheduleController::class, 'index'])->name('web.sms-schedule.index');
    Route::get('sms-schedule/{smsSchedule}', [SmsScheduleController::class, 'show'])->name('web.sms-schedule.show');
    Route::get('sms-schedule/export/csv', [SmsScheduleController::class, 'export'])->name('web.sms-schedule.export');

    // SMS Pool Archive Routes
    Route::get('sms-pool-archive', [SmsPoolArchiveController::class, 'index'])->name('web.sms-pool-archive.index');
    Route::get('sms-pool-archive/{smsPoolArchive}', [SmsPoolArchiveController::class, 'show'])->name('web.sms-pool-archive.show');
    Route::get('sms-pool-archive/export/csv', [SmsPoolArchiveController::class, 'export'])->name('web.sms-pool-archive.export');

    // Email Pool Archive Routes
    Route::get('email-pool-archive', [EmailPoolArchiveController::class, 'index'])->name('web.email-pool-archive.index');
    Route::get('email-pool-archive/{emailPoolArchive}', [EmailPoolArchiveController::class, 'show'])->name('web.email-pool-archive.show');
    Route::get('email-pool-archive/{emailPoolArchive}/view-body', [EmailPoolArchiveController::class, 'viewBody'])->name('web.email-pool-archive.view-body');
    Route::get('email-pool-archive/attachment/{attachment}', [EmailPoolArchiveController::class, 'attachment'])->name('web.email-pool-archive.attachment');
    Route::delete('email-pool-archive/{emailPoolArchive}', [EmailPoolArchiveController::class, 'destroy'])->name('web.email-pool-archive.destroy');
    Route::get('email-pool-archive/export/csv', [EmailPoolArchiveController::class, 'export'])->name('web.email-pool-archive.export');

    // API Log Routes
    Route::get('api-log', [ApiLogController::class, 'index'])->name('web.api-log.index');
    Route::get('api-log/{apiLog}', [ApiLogController::class, 'show'])->name('web.api-log.show');
    Route::delete('api-log/{apiLog}/delete-by-key', [ApiLogController::class, 'deleteByKey'])->name('web.api-log.delete-by-key');
    Route::get('api-log/export/csv', [ApiLogController::class, 'export'])->name('web.api-log.export');

    // Active Record Log Routes
    Route::get('active-record-log', [ActiveRecordLogController::class, 'index'])->name('web.active-record-log.index');
    Route::get('active-record-log/export/csv', [ActiveRecordLogController::class, 'export'])->name('web.active-record-log.export');

    // Admin Message Log Routes
    Route::get('admin-message-log', [AdminMessageLogController::class, 'index'])->name('web.admin-message-log.index');
    Route::get('admin-message-log/export/csv', [AdminMessageLogController::class, 'export'])->name('web.admin-message-log.export');

    // Contract Line Routes
    Route::get('contract-line/unpaid', [ContractLineController::class, 'unpaid'])->name('web.contract-line.unpaid');
    Route::get('contract-line/unpaid/export/csv', [ContractLineController::class, 'exportUnpaid'])->name('web.contract-line.export-unpaid');
});

// ========================================
// API ROUTES (Keep existing)
// ========================================
