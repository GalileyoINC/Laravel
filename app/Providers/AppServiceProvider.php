<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\AllSendForm\AllSendFormService;
use App\Services\AllSendForm\AllSendFormServiceInterface;
use App\Services\Authentication\AuthService;
use App\Services\Authentication\AuthServiceInterface;
use App\Services\Bookmark\BookmarkService;
use App\Services\Bookmark\BookmarkServiceInterface;
use App\Services\Bundle\BundleService;
use App\Services\Bundle\BundleServiceInterface;
use App\Services\Chat\ChatService;
use App\Services\Chat\ChatServiceInterface;
use App\Services\Comment\CommentService;
use App\Services\Comment\CommentServiceInterface;
use App\Services\Contact\ContactService;
use App\Services\Contact\ContactServiceInterface;
use App\Services\ContractLine\ContractLineService;
use App\Services\ContractLine\ContractLineServiceInterface;
use App\Services\CreditCard\CreditCardService;
use App\Services\CreditCard\CreditCardServiceInterface;
use App\Services\Customer\CustomerService;
use App\Services\Customer\CustomerServiceInterface;
use App\Services\Device\DeviceService;
use App\Services\Device\DeviceServiceInterface;
use App\Services\EmailPool\EmailPoolService;
use App\Services\EmailPool\EmailPoolServiceInterface;
use App\Services\EmailTemplate\EmailTemplateService;
use App\Services\EmailTemplate\EmailTemplateServiceInterface;
use App\Services\Influencer\InfluencerService;
use App\Services\Influencer\InfluencerServiceInterface;
use App\Services\Invoice\InvoiceService;
use App\Services\Invoice\InvoiceServiceInterface;
use App\Services\News\NewsService;
use App\Services\News\NewsServiceInterface;
use App\Services\Order\OrderService;
use App\Services\Order\OrderServiceInterface;
use App\Services\Phone\PhoneService;
use App\Services\Phone\PhoneServiceInterface;
use App\Services\PrivateFeed\PrivateFeedService;
use App\Services\PrivateFeed\PrivateFeedServiceInterface;
use App\Services\Product\ProductService;
use App\Services\Product\ProductServiceInterface;
use App\Services\PublicFeed\PublicFeedService;
use App\Services\PublicFeed\PublicFeedServiceInterface;
use App\Services\Report\ReportService;
use App\Services\Report\ReportServiceInterface;
use App\Services\Settings\SettingsService;
use App\Services\Settings\SettingsServiceInterface;
use App\Services\Staff\StaffService;
use App\Services\Staff\StaffServiceInterface;
use App\Services\Subscription\SubscriptionService;
use App\Services\Subscription\SubscriptionServiceInterface;
use App\Services\Users\UsersService;
use App\Services\Users\UsersServiceInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register Authentication services
        $this->app->bind(
            AuthServiceInterface::class,
            AuthService::class
        );

        // Register Chat services
        $this->app->bind(
            ChatServiceInterface::class,
            ChatService::class
        );

        // Register Comment services
        $this->app->bind(
            CommentServiceInterface::class,
            CommentService::class
        );

        // Register CreditCard services
        $this->app->bind(
            CreditCardServiceInterface::class,
            CreditCardService::class
        );

        // Register Device services
        $this->app->bind(
            DeviceServiceInterface::class,
            DeviceService::class
        );

        // Register Influencer services
        $this->app->bind(
            InfluencerServiceInterface::class,
            InfluencerService::class
        );

        // Register News services
        $this->app->bind(
            NewsServiceInterface::class,
            NewsService::class
        );

        // Register Order services
        $this->app->bind(
            OrderServiceInterface::class,
            OrderService::class
        );

        // Register Phone services
        $this->app->bind(
            PhoneServiceInterface::class,
            PhoneService::class
        );

        // Register PrivateFeed services
        $this->app->bind(
            PrivateFeedServiceInterface::class,
            PrivateFeedService::class
        );

        // Register Product services
        $this->app->bind(
            ProductServiceInterface::class,
            ProductService::class
        );

        // Register PublicFeed services
        $this->app->bind(
            PublicFeedServiceInterface::class,
            PublicFeedService::class
        );

        // Register Subscription services
        $this->app->bind(
            SubscriptionServiceInterface::class,
            SubscriptionService::class
        );

        // Register AllSendForm services
        $this->app->bind(
            AllSendFormServiceInterface::class,
            AllSendFormService::class
        );

        // Register Customer services
        $this->app->bind(
            CustomerServiceInterface::class,
            CustomerService::class
        );

        // Register Users services
        $this->app->bind(
            UsersServiceInterface::class,
            UsersService::class
        );

        // ===== NEWLY MIGRATED SERVICES =====

        // Register Bookmark services
        $this->app->bind(
            BookmarkServiceInterface::class,
            BookmarkService::class
        );

        // Register Bundle services
        $this->app->bind(
            BundleServiceInterface::class,
            BundleService::class
        );

        // Register Contact services
        $this->app->bind(
            ContactServiceInterface::class,
            ContactService::class
        );

        // Register ContractLine services
        $this->app->bind(
            ContractLineServiceInterface::class,
            ContractLineService::class
        );

        // Register EmailPool services
        $this->app->bind(
            EmailPoolServiceInterface::class,
            EmailPoolService::class
        );

        // Register EmailTemplate services
        $this->app->bind(
            EmailTemplateServiceInterface::class,
            EmailTemplateService::class
        );

        // Register Invoice services
        $this->app->bind(
            InvoiceServiceInterface::class,
            InvoiceService::class
        );

        // Register Report services
        $this->app->bind(
            ReportServiceInterface::class,
            ReportService::class
        );

        // Register Settings services
        $this->app->bind(
            SettingsServiceInterface::class,
            SettingsService::class
        );

        // Register Staff services
        $this->app->bind(
            StaffServiceInterface::class,
            StaffService::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
