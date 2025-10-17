<?php

declare(strict_types=1);

namespace Tests\Unit\Unit\Subscription;

use App\Domain\DTOs\Subscription\FeedOptionsDTO;
use App\Domain\DTOs\Subscription\SubscriptionRequestDTO;
use App\Domain\Services\Subscription\SubscriptionService;
use App\Models\Subscription\Subscription;
use App\Models\Subscription\SubscriptionCategory;
use App\Models\User\User;
use App\Models\User\UserSubscription;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscriptionServiceTest extends TestCase
{
    use RefreshDatabase;

    private SubscriptionService $subscriptionService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
        $this->subscriptionService = new SubscriptionService();
    }

    /** @test */
    public function set_subscription_creates_user_subscription(): void
    {
        // Arrange
        $user = User::factory()->create();
        $category = SubscriptionCategory::factory()->create();
        $subscription = Subscription::factory()->create([
            'name' => 'Test Subscription',
            'is_active' => true,
            'id_subscription_category' => $category->id,
        ]);

        $dto = new SubscriptionRequestDTO(
            idSubscription: $subscription->id,
            checked: true,
            zip: '12345',
            subType: 'regular'
        );

        // Act
        $result = $this->subscriptionService->setSubscription($dto, $user);

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('success', $result);
        $this->assertTrue($result['success']);

        // Verify subscription was created in database
        $this->assertDatabaseHas('user_subscription', [
            'id_user' => $user->id,
            'id_subscription' => $subscription->id,
        ]);
    }

    /** @test */
    public function set_subscription_removes_user_subscription_when_unchecked(): void
    {
        // Arrange
        $user = User::factory()->create();
        $category = SubscriptionCategory::factory()->create();
        $subscription = Subscription::factory()->create([
            'id_subscription_category' => $category->id,
        ]);

        // Create existing subscription
        UserSubscription::factory()->create([
            'id_user' => $user->id,
            'id_subscription' => $subscription->id,
        ]);

        $dto = new SubscriptionRequestDTO(
            idSubscription: $subscription->id,
            checked: false,
            zip: null,
            subType: 'regular'
        );

        // Act
        $result = $this->subscriptionService->setSubscription($dto, $user);

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('success', $result);
        $this->assertTrue($result['success']);

        // Verify subscription was removed from database
        $this->assertDatabaseMissing('user_subscription', [
            'id_user' => $user->id,
            'id_subscription' => $subscription->id,
        ]);
    }

    /** @test */
    public function set_subscription_updates_existing_subscription(): void
    {
        // Arrange
        $user = User::factory()->create();
        $category = SubscriptionCategory::factory()->create();
        $subscription = Subscription::factory()->create([
            'id_subscription_category' => $category->id,
        ]);

        // Create existing subscription
        UserSubscription::factory()->create([
            'id_user' => $user->id,
            'id_subscription' => $subscription->id,
        ]);

        $dto = new SubscriptionRequestDTO(
            idSubscription: $subscription->id,
            checked: true,
            zip: '22222',
            subType: 'satellite'
        );

        // Act
        $result = $this->subscriptionService->setSubscription($dto, $user);

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('success', $result);
        $this->assertTrue($result['success']);

        // Verify subscription was updated in database
        $this->assertDatabaseHas('user_subscription', [
            'id_user' => $user->id,
            'id_subscription' => $subscription->id,
        ]);

        // Verify only one subscription exists
        $this->assertDatabaseCount('user_subscription', 1);
    }

    /** @test */
    public function set_subscription_throws_exception_for_non_existent_subscription(): void
    {
        // Arrange
        $user = User::factory()->create();
        $nonExistentId = 99999;

        $dto = new SubscriptionRequestDTO(
            idSubscription: $nonExistentId,
            checked: true,
            zip: '12345',
            subType: 'regular'
        );

        // Act & Assert
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Subscription not found');

        $this->subscriptionService->setSubscription($dto, $user);
    }

    /** @test */
    public function get_feed_categories_returns_categories(): void
    {
        // Arrange
        $category1 = SubscriptionCategory::factory()->create(['name' => 'Category 1']);
        $category2 = SubscriptionCategory::factory()->create(['name' => 'Category 2']);
        $category3 = SubscriptionCategory::factory()->create(['name' => 'Category 3']);

        Subscription::factory()->create([
            'is_active' => true,
            'is_public' => true,
            'id_subscription_category' => $category1->id,
        ]);
        Subscription::factory()->create([
            'is_active' => true,
            'is_public' => true,
            'id_subscription_category' => $category2->id,
        ]);
        Subscription::factory()->create([
            'is_active' => true,
            'is_public' => true,
            'id_subscription_category' => $category3->id,
        ]);

        // Act
        $result = $this->subscriptionService->getFeedCategories();

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('categories', $result);
        $this->assertCount(3, $result['categories']);
    }

    /** @test */
    public function get_feed_categories_only_returns_active_subscriptions(): void
    {
        // Arrange
        $category1 = SubscriptionCategory::factory()->create(['name' => 'Active Category 1']);
        $category2 = SubscriptionCategory::factory()->create(['name' => 'Active Category 2']);

        Subscription::factory()->count(2)->create([
            'is_active' => true,
            'is_public' => true,
            'id_subscription_category' => $category1->id,
        ]);
        Subscription::factory()->count(2)->create([
            'is_active' => true,
            'is_public' => true,
            'id_subscription_category' => $category2->id,
        ]);
        Subscription::factory()->count(2)->create([
            'is_active' => false,
            'is_public' => true,
            'id_subscription_category' => $category1->id,
        ]);

        // Act
        $result = $this->subscriptionService->getFeedCategories();

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('categories', $result);
        $this->assertCount(2, $result['categories']);
    }

    /** @test */
    public function get_feed_list_returns_user_subscriptions(): void
    {
        // Arrange
        $user = User::factory()->create();
        $category = SubscriptionCategory::factory()->create();
        $subscription1 = Subscription::factory()->create([
            'is_active' => true,
            'is_public' => true,
            'id_subscription_category' => $category->id,
        ]);
        $subscription2 = Subscription::factory()->create([
            'is_active' => true,
            'is_public' => true,
            'id_subscription_category' => $category->id,
        ]);

        // Create user subscriptions
        UserSubscription::factory()->create([
            'id_user' => $user->id,
            'id_subscription' => $subscription1->id,
        ]);
        UserSubscription::factory()->create([
            'id_user' => $user->id,
            'id_subscription' => $subscription2->id,
        ]);

        // Act
        $dto = new FeedOptionsDTO();
        $result = $this->subscriptionService->getFeedList($dto, $user);

        // Assert
        $this->assertIsArray($result);
        $this->assertCount(2, $result);
        $this->assertArrayHasKey('is_subscribed', $result[0]);
        $this->assertTrue($result[0]['is_subscribed']);
    }

    /** @test */
    public function get_feed_list_returns_empty_for_user_without_subscriptions(): void
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $dto = new FeedOptionsDTO();
        $result = $this->subscriptionService->getFeedList($dto, $user);

        // Assert
        $this->assertIsArray($result);
        $this->assertCount(0, $result);
    }
}
