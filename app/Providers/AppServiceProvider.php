<?php

declare(strict_types=1);

namespace App\Providers;

use App\Domain\Services\AllSendForm\AllSendFormService;
use App\Domain\Services\AllSendForm\AllSendFormServiceInterface;
use App\Domain\Services\Authentication\AuthService;
use App\Domain\Services\Authentication\AuthServiceInterface;
use App\Domain\Services\Bookmark\BookmarkService;
use App\Domain\Services\Bookmark\BookmarkServiceInterface;
use App\Domain\Services\Bundle\BundleService;
use App\Domain\Services\Bundle\BundleServiceInterface;
use App\Domain\Services\Chat\ChatService;
use App\Domain\Services\Chat\ChatServiceInterface;
use App\Domain\Services\Comment\CommentService;
use App\Domain\Services\Comment\CommentServiceInterface;
use App\Domain\Services\Contact\ContactService;
use App\Domain\Services\Contact\ContactServiceInterface;
use App\Domain\Services\ContractLine\ContractLineService;
use App\Domain\Services\ContractLine\ContractLineServiceInterface;
use App\Domain\Services\CreditCard\CreditCardService;
use App\Domain\Services\CreditCard\CreditCardServiceInterface;
use App\Domain\Services\Customer\CustomerService;
use App\Domain\Services\Customer\CustomerServiceInterface;
use App\Domain\Services\Device\DeviceService;
use App\Domain\Services\Device\DeviceServiceInterface;
use App\Domain\Services\EmailPool\EmailPoolService;
use App\Domain\Services\EmailPool\EmailPoolServiceInterface;
use App\Domain\Services\EmailTemplate\EmailTemplateService;
use App\Domain\Services\EmailTemplate\EmailTemplateServiceInterface;
use App\Domain\Services\Influencer\InfluencerService;
use App\Domain\Services\Influencer\InfluencerServiceInterface;
use App\Domain\Services\Maintenance\MaintenanceService;
use App\Domain\Services\Maintenance\MaintenanceServiceInterface;
use App\Domain\Services\News\NewsService;
use App\Domain\Services\News\NewsServiceInterface;
use App\Domain\Services\Order\OrderService;
use App\Domain\Services\Order\OrderServiceInterface;
use App\Domain\Services\Phone\PhoneService;
use App\Domain\Services\Phone\PhoneServiceInterface;
use App\Domain\Services\Posts\PostsService;
use App\Domain\Services\Posts\PostsServiceInterface;
use App\Domain\Services\PrivateFeed\PrivateFeedService;
use App\Domain\Services\PrivateFeed\PrivateFeedServiceInterface;
use App\Domain\Services\Payment\PaymentService;
use App\Domain\Services\Payment\PaymentServiceInterface;
use App\Domain\Services\Product\ProductAlertMapService;
use App\Domain\Services\Push\PushService;
use App\Domain\Services\Push\PushServiceInterface;
use App\Domain\Services\Product\ProductAlertMapServiceInterface;
use App\Domain\Services\Product\ProductService;
use App\Domain\Services\Product\ProductServiceInterface;
use App\Domain\Services\PublicFeed\PublicFeedService;
use App\Domain\Services\PublicFeed\PublicFeedServiceInterface;
use App\Domain\Services\Report\ReportService;
use App\Domain\Services\Report\ReportServiceInterface;
use App\Domain\Services\Search\SearchService;
use App\Domain\Services\Search\SearchServiceInterface;
use App\Domain\Services\Settings\SettingsService;
use App\Domain\Services\Settings\SettingsServiceInterface;
use App\Domain\Services\Subscription\SubscriptionService;
use App\Domain\Services\Subscription\SubscriptionServiceInterface;
use App\Domain\Services\Users\UsersService;
use App\Domain\Services\Users\UsersServiceInterface;
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

        // Register Posts services
        $this->app->bind(
            PostsServiceInterface::class,
            PostsService::class
        );

        // Register PrivateFeed services
        $this->app->bind(
            PrivateFeedServiceInterface::class,
            PrivateFeedService::class
        );

        // Register Payment services
        $this->app->bind(
            PaymentServiceInterface::class,
            PaymentService::class
        );

        // Register Product services
        $this->app->bind(
            ProductServiceInterface::class,
            ProductService::class
        );

        // Register ProductAlertMap services
        $this->app->bind(
            ProductAlertMapServiceInterface::class,
            ProductAlertMapService::class
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

        // Register Report services
        $this->app->bind(
            ReportServiceInterface::class,
            ReportService::class
        );

        // Register Search services
        $this->app->bind(
            SearchServiceInterface::class,
            SearchService::class
        );

        // Register Settings services
        $this->app->bind(
            SettingsServiceInterface::class,
            SettingsService::class
        );

        // Register Maintenance services
        $this->app->bind(
            MaintenanceServiceInterface::class,
            MaintenanceService::class
        );

        // Register Push services
        $this->app->bind(
            PushServiceInterface::class,
            PushService::class
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
