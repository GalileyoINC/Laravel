<?php

namespace App\Providers;

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
            \App\Services\Authentication\AuthServiceInterface::class,
            \App\Services\Authentication\AuthService::class
        );

        // Register Chat services
        $this->app->bind(
            \App\Services\Chat\ChatServiceInterface::class,
            \App\Services\Chat\ChatService::class
        );

        // Register Comment services
        $this->app->bind(
            \App\Services\Comment\CommentServiceInterface::class,
            \App\Services\Comment\CommentService::class
        );

        // Register CreditCard services
        $this->app->bind(
            \App\Services\CreditCard\CreditCardServiceInterface::class,
            \App\Services\CreditCard\CreditCardService::class
        );

        // Register Device services
        $this->app->bind(
            \App\Services\Device\DeviceServiceInterface::class,
            \App\Services\Device\DeviceService::class
        );

        // Register Influencer services
        $this->app->bind(
            \App\Services\Influencer\InfluencerServiceInterface::class,
            \App\Services\Influencer\InfluencerService::class
        );

        // Register News services
        $this->app->bind(
            \App\Services\News\NewsServiceInterface::class,
            \App\Services\News\NewsService::class
        );

        // Register Order services
        $this->app->bind(
            \App\Services\Order\OrderServiceInterface::class,
            \App\Services\Order\OrderService::class
        );

        // Register Phone services
        $this->app->bind(
            \App\Services\Phone\PhoneServiceInterface::class,
            \App\Services\Phone\PhoneService::class
        );

        // Register PrivateFeed services
        $this->app->bind(
            \App\Services\PrivateFeed\PrivateFeedServiceInterface::class,
            \App\Services\PrivateFeed\PrivateFeedService::class
        );

        // Register Product services
        $this->app->bind(
            \App\Services\Product\ProductServiceInterface::class,
            \App\Services\Product\ProductService::class
        );

        // Register PublicFeed services
        $this->app->bind(
            \App\Services\PublicFeed\PublicFeedServiceInterface::class,
            \App\Services\PublicFeed\PublicFeedService::class
        );

        // Register Subscription services
        $this->app->bind(
            \App\Services\Subscription\SubscriptionServiceInterface::class,
            \App\Services\Subscription\SubscriptionService::class
        );

        // Register AllSendForm services
        $this->app->bind(
            \App\Services\AllSendForm\AllSendFormServiceInterface::class,
            \App\Services\AllSendForm\AllSendFormService::class
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
