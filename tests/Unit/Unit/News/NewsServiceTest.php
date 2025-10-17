<?php

declare(strict_types=1);

namespace Tests\Unit\Unit\News;

use App\Domain\DTOs\News\NewsListRequestDTO;
use App\Domain\DTOs\News\ReactionRequestDTO;
use App\Domain\Services\News\NewsService;
use App\Models\Communication\SmsPool;
use App\Models\Communication\SmsPoolReaction;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsServiceTest extends TestCase
{
    use RefreshDatabase;

    private NewsService $newsService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
        $this->newsService = new NewsService();
    }

    /** @test */
    public function get_last_news_returns_news_list(): void
    {
        // Arrange
        $user = User::factory()->create();
        $smsPool = SmsPool::factory()->create([
            'purpose' => 1,
            'body' => 'Test news body',
            'short_body' => 'Test news',
            'id_subscription' => null, // Don't create subscription
        ]);

        $dto = new NewsListRequestDTO(
            type: null, // Don't filter by type
            limit: 10,
            offset: 0,
            search: null
        );

        // Act
        $result = $this->newsService->getLastNews($dto, $user);

        // Assert
        $this->assertNotNull($result);
        $this->assertCount(1, $result);
        $this->assertEquals($smsPool->id, $result->first()->id);
        $this->assertEquals('general', $result->first()->type);
        $this->assertEquals('Test news', $result->first()->title);
    }

    /** @test */
    public function get_last_news_with_search_filter(): void
    {
        // Arrange
        $user = User::factory()->create();
        SmsPool::factory()->create([
            'body' => 'Financial news about stocks',
            'short_body' => 'Stock update',
            'id_subscription' => null,
        ]);
        SmsPool::factory()->create([
            'body' => 'Sports news about football',
            'short_body' => 'Football match',
            'id_subscription' => null,
        ]);

        $dto = new NewsListRequestDTO(
            type: null,
            limit: 10,
            offset: 0,
            search: 'financial'
        );

        // Act
        $result = $this->newsService->getLastNews($dto, $user);

        // Assert
        $this->assertCount(1, $result);
        $this->assertStringContainsString('financial', mb_strtolower($result->first()->body));
    }

    /** @test */
    public function get_news_by_influencers_returns_influencer_news(): void
    {
        // Arrange
        $user = User::factory()->create();
        SmsPool::factory()->create([
            'purpose' => 1, // General purpose
            'id_subscription' => null,
        ]);
        SmsPool::factory()->create([
            'purpose' => 2, // Influencer purpose
            'body' => 'Influencer post',
            'id_subscription' => null,
        ]);

        $dto = new NewsListRequestDTO(
            type: null,
            limit: 10,
            offset: 0,
            search: null
        );

        // Act
        $result = $this->newsService->getNewsByInfluencers($dto, $user);

        // Assert
        $this->assertCount(1, $result);
        $this->assertEquals(2, $result->first()->purpose);
        $this->assertEquals('influencer', $result->first()->type);
    }

    /** @test */
    public function set_reaction_creates_new_reaction(): void
    {
        // Arrange
        $user = User::factory()->create();
        $smsPool = SmsPool::factory()->create([
            'id_subscription' => null,
        ]);

        // Create reaction emoji first
        $reactionEmoji = \App\Models\Content\Reaction::create(['emoji' => '👍']);

        $dto = new ReactionRequestDTO(
            idNews: $smsPool->id,
            reactionType: (string) $reactionEmoji->id,
            message: 'Great news!'
        );

        // Act
        $result = $this->newsService->setReaction($dto, $user);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($smsPool->id, $result->id_sms_pool);
        $this->assertEquals($user->id, $result->id_user);
        $this->assertEquals($reactionEmoji->id, $result->id_reaction);

        // Verify reaction was saved to database
        $this->assertDatabaseHas('sms_pool_reaction', [
            'id_sms_pool' => $smsPool->id,
            'id_user' => $user->id,
            'id_reaction' => $reactionEmoji->id,
        ]);
    }

    /** @test */
    public function set_reaction_updates_existing_reaction(): void
    {
        // Arrange
        $user = User::factory()->create();
        $smsPool = SmsPool::factory()->create([
            'id_subscription' => null,
        ]);

        // Create reaction emojis
        $reactionLike = \App\Models\Content\Reaction::create(['emoji' => '👍']);
        $reactionLove = \App\Models\Content\Reaction::create(['emoji' => '❤️']);

        // Create existing reaction
        SmsPoolReaction::factory()->create([
            'id_sms_pool' => $smsPool->id,
            'id_user' => $user->id,
            'id_reaction' => $reactionLike->id,
        ]);

        $dto = new ReactionRequestDTO(
            idNews: $smsPool->id,
            reactionType: (string) $reactionLove->id,
            message: 'Updated message'
        );

        // Act
        $result = $this->newsService->setReaction($dto, $user);

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($reactionLove->id, $result->id_reaction);

        // Verify only one reaction exists
        $this->assertDatabaseCount('sms_pool_reaction', 1);
    }

    /** @test */
    public function remove_reaction_deletes_existing_reaction(): void
    {
        // Arrange
        $user = User::factory()->create();
        $smsPool = SmsPool::factory()->create([
            'id_subscription' => null,
        ]);

        // Create reaction emoji
        $reactionLike = \App\Models\Content\Reaction::create(['emoji' => '👍']);

        SmsPoolReaction::factory()->create([
            'id_sms_pool' => $smsPool->id,
            'id_user' => $user->id,
            'id_reaction' => $reactionLike->id,
        ]);

        $dto = new ReactionRequestDTO(
            idNews: $smsPool->id,
            reactionType: (string) $reactionLike->id,
            message: null
        );

        // Act
        $result = $this->newsService->removeReaction($dto, $user);

        // Assert
        $this->assertEquals(['success' => true, 'message' => 'Reaction removed successfully'], $result);
        $this->assertDatabaseMissing('sms_pool_reaction', [
            'id_sms_pool' => $smsPool->id,
            'id_user' => $user->id,
        ]);
    }

    /** @test */
    public function remove_reaction_returns_error_for_non_existent_reaction(): void
    {
        // Arrange
        $user = User::factory()->create();
        $smsPool = SmsPool::factory()->create([
            'id_subscription' => null,
        ]);

        $dto = new ReactionRequestDTO(
            idNews: $smsPool->id,
            reactionType: 'like',
            message: null
        );

        // Act
        $result = $this->newsService->removeReaction($dto, $user);

        // Assert
        $this->assertEquals(['success' => false, 'message' => 'Reaction not found'], $result);
    }

    /** @test */
    public function get_feed_item_type_returns_correct_type(): void
    {
        // This tests the private method indirectly through the service
        $user = User::factory()->create();

        // Test different purposes
        $generalNews = SmsPool::factory()->create([
            'purpose' => 1,
            'id_subscription' => null,
        ]);
        $influencerNews = SmsPool::factory()->create([
            'purpose' => 2,
            'id_subscription' => null,
        ]);
        $subscriptionNews = SmsPool::factory()->create([
            'purpose' => 3,
            'id_subscription' => null,
        ]);

        $dto = new NewsListRequestDTO(limit: 10, offset: 0);

        // Act
        $result = $this->newsService->getLastNews($dto, $user);

        // Assert
        $this->assertCount(3, $result);

        $types = $result->pluck('type')->toArray();
        $this->assertContains('general', $types);
        $this->assertContains('influencer', $types);
        $this->assertContains('subscription', $types);
    }
}
