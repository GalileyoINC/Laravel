<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Subscription;
use App\Models\News;
use App\Models\Comment;
use App\Models\CreditCard;
use App\Models\Device;
use App\Models\SmsPool;
use App\Models\FollowerList;
use App\Models\InfluencerPage;
use App\Models\UserSubscription;
use App\Models\Follower;
use App\Models\Reaction;
use App\Models\SmsPoolReaction;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🌱 Starting database seeding...');

        // Create users
        $this->command->info('👥 Creating users...');
        $users = User::factory(50)->create();
        $this->command->info("✅ Created {$users->count()} users");

        // Create subscription categories first
        $this->command->info('📂 Creating subscription categories...');
        $categories = \App\Models\SubscriptionCategory::factory(10)->create();
        $this->command->info("✅ Created {$categories->count()} subscription categories");

        // Create subscriptions
        $this->command->info('📰 Creating subscriptions...');
        $subscriptions = Subscription::factory(30)->create();
        $this->command->info("✅ Created {$subscriptions->count()} subscriptions");

        // Create news
        $this->command->info('📰 Creating news...');
        $news = News::factory(100)->create();
        $this->command->info("✅ Created {$news->count()} news articles");

        // Create comments
        $this->command->info('💬 Creating comments...');
        $comments = Comment::factory(200)->create();
        $this->command->info("✅ Created {$comments->count()} comments");

        // Create credit cards
        $this->command->info('💳 Creating credit cards...');
        $creditCards = CreditCard::factory(75)->create();
        $this->command->info("✅ Created {$creditCards->count()} credit cards");

        // Create devices
        $this->command->info('📱 Creating devices...');
        $devices = Device::factory(60)->create();
        $this->command->info("✅ Created {$devices->count()} devices");

        // Create SMS pools
        $this->command->info('📨 Creating SMS pools...');
        $smsPools = SmsPool::factory(150)->create();
        $this->command->info("✅ Created {$smsPools->count()} SMS pools");

        // Create follower lists
        $this->command->info('👥 Creating follower lists...');
        $followerLists = FollowerList::factory(40)->create();
        $this->command->info("✅ Created {$followerLists->count()} follower lists");

        // Create influencer pages
        $this->command->info('🌟 Creating influencer pages...');
        $influencerPages = InfluencerPage::factory(25)->create();
        $this->command->info("✅ Created {$influencerPages->count()} influencer pages");

        // Create user subscriptions
        $this->command->info('🔗 Creating user subscriptions...');
        foreach ($users->take(30) as $user) {
            $userSubscriptions = $subscriptions->random(rand(1, 5));
            foreach ($userSubscriptions as $subscription) {
                UserSubscription::factory()->create([
                    'id_user' => $user->id,
                    'id_subscription' => $subscription->id,
                ]);
            }
        }
        $this->command->info("✅ Created user subscriptions");

        // Create followers
        $this->command->info('👥 Creating followers...');
        foreach ($followerLists->take(20) as $followerList) {
            $followers = $users->random(rand(5, 15));
            foreach ($followers as $follower) {
                Follower::factory()->create([
                    'id_follower_list' => $followerList->id,
                    'id_user_follower' => $follower->id,
                ]);
            }
        }
        $this->command->info("✅ Created followers");

        // Create reactions
        $this->command->info('👍 Creating reactions...');
        $emojis = ['👍', '👎', '❤️', '😀', '😢', '😮', '😡', '🔥', '💯', '🎉', '👏', '🤔', '😍', '😂', '😭'];
        $reactions = collect();
        foreach ($emojis as $emoji) {
            $reactions->push(Reaction::firstOrCreate(['emoji' => $emoji]));
        }
        $this->command->info("✅ Created {$reactions->count()} reactions");

        // Create SMS pool reactions
        $this->command->info('👍 Creating SMS pool reactions...');
        foreach ($smsPools->take(50) as $smsPool) {
            $userReactions = $users->random(rand(1, 10));
            foreach ($userReactions as $user) {
                SmsPoolReaction::factory()->create([
                    'id_sms_pool' => $smsPool->id,
                    'id_user' => $user->id,
                    'id_reaction' => $reactions->random()->id,
                ]);
            }
        }
        $this->command->info("✅ Created SMS pool reactions");

        // Create some specific demo users
        $this->command->info('👤 Creating demo users...');
        
        // Admin user
        User::factory()->create([
            'email' => 'admin@galileyo.com',
            'first_name' => 'Admin',
            'last_name' => 'User',
            'role' => 1,
            'is_valid_email' => true,
        ]);

        // Test user
        User::factory()->create([
            'email' => 'test@galileyo.com',
            'first_name' => 'Test',
            'last_name' => 'User',
            'role' => 2,
            'is_valid_email' => true,
        ]);

        // Influencer user
        User::factory()->influencer()->create([
            'email' => 'influencer@galileyo.com',
            'first_name' => 'Influencer',
            'last_name' => 'User',
            'role' => 2,
            'is_valid_email' => true,
        ]);

        $this->command->info("✅ Created demo users");

        $this->command->info('🎉 Database seeding completed successfully!');
        $this->command->info('');
        $this->command->info('Demo users created:');
        $this->command->info('👤 Admin: admin@galileyo.com');
        $this->command->info('👤 Test: test@galileyo.com');
        $this->command->info('🌟 Influencer: influencer@galileyo.com');
        $this->command->info('');
        $this->command->info('All users have password: "password"');
    }
}