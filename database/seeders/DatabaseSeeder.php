<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Communication\SmsPool;
use App\Models\Communication\SmsPoolReaction;
use App\Models\Content\Comment;
use App\Models\Content\News;
use App\Models\Content\Reaction;
use App\Models\Device\Device;
use App\Models\Finance\CreditCard;
use App\Models\Subscription\Follower;
use App\Models\Subscription\FollowerList;
use App\Models\Subscription\InfluencerPage;
use App\Models\Subscription\Subscription;
use App\Models\Subscription\SubscriptionCategory;
use App\Models\User\User;
use App\Models\User\UserSubscription;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🌱 Starting database seeding...');

        // Run the demo data seeder first
        $this->call(DemoDataSeeder::class);

        // Continue with existing seeding logic
        $this->command->info('👥 Creating additional users...');
        $users = User::factory(50)->create();
        $this->command->info("✅ Created {$users->count()} additional users");

        // Create subscription categories first
        $this->command->info('📂 Creating subscription categories...');
        $categories = SubscriptionCategory::factory(10)->create();
        $this->command->info("✅ Created {$categories->count()} subscription categories");

        // Create subscriptions
        $this->command->info('📰 Creating subscriptions...');
        $subscriptions = Subscription::factory(30)->create();
        $this->command->info("✅ Created {$subscriptions->count()} subscriptions");

        // Create news
        $this->command->info('📰 Creating additional news...');
        $news = News::factory(100)->create();
        $this->command->info("✅ Created {$news->count()} additional news articles");

        // Create comments
        $this->command->info('💬 Creating comments...');
        $comments = Comment::factory(200)->create();
        $this->command->info("✅ Created {$comments->count()} comments");

        // Create credit cards
        $this->command->info('💳 Creating credit cards...');
        $creditCards = CreditCard::factory(75)->create();
        $this->command->info("✅ Created {$creditCards->count()} credit cards");

        // Create devices
        $this->command->info('📱 Creating additional devices...');
        $devices = Device::factory(60)->create();
        $this->command->info("✅ Created {$devices->count()} additional devices");

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
            $userSubscriptions = $subscriptions->random(random_int(1, 5));
            foreach ($userSubscriptions as $subscription) {
                UserSubscription::factory()->create([
                    'id_user' => $user->id,
                    'id_subscription' => $subscription->id,
                ]);
            }
        }
        $this->command->info('✅ Created user subscriptions');

        // Create followers
        $this->command->info('👥 Creating followers...');
        foreach ($followerLists->take(20) as $followerList) {
            $followers = $users->random(random_int(5, 15));
            foreach ($followers as $follower) {
                Follower::factory()->create([
                    'id_follower_list' => $followerList->id,
                    'id_user_follower' => $follower->id,
                ]);
            }
        }
        $this->command->info('✅ Created followers');

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
            $userReactions = $users->random(random_int(1, 10));
            foreach ($userReactions as $user) {
                SmsPoolReaction::factory()->create([
                    'id_sms_pool' => $smsPool->id,
                    'id_user' => $user->id,
                    'id_reaction' => $reactions->random()->id,
                ]);
            }
        }
        $this->command->info('✅ Created SMS pool reactions');

        // Create some specific demo users
        $this->command->info('👤 Creating demo users...');

        // Admin user
        User::firstOrCreate(
            ['email' => 'admin@galileyo.com'],
            User::factory()->make([
                'first_name' => 'Admin',
                'last_name' => 'User',
                'role' => 1,
                'is_valid_email' => true,
            ])->toArray()
        );

        // Test user
        User::firstOrCreate(
            ['email' => 'test@galileyo.com'],
            User::factory()->make([
                'first_name' => 'Test',
                'last_name' => 'User',
                'role' => 2,
                'is_valid_email' => true,
            ])->toArray()
        );

        // Influencer user
        User::firstOrCreate(
            ['email' => 'influencer@galileyo.com'],
            User::factory()->influencer()->make([
                'first_name' => 'Influencer',
                'last_name' => 'User',
                'role' => 2,
                'is_valid_email' => true,
            ])->toArray()
        );

        $this->command->info('✅ Created demo users');

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
