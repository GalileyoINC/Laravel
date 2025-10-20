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
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
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
        User::updateOrCreate(
            ['email' => 'admin@galileyo.com'],
            array_merge(
                User::factory()->make([
                    'first_name' => 'Admin',
                    'last_name' => 'User',
                    'role' => 1,
                    'is_valid_email' => true,
                    'status' => 1,
                ])->toArray(),
                ['password_hash' => Hash::make('pass')]
            )
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

        // Seed any remaining models that have factories but empty tables
        $this->command->info('🧩 Seeding remaining models with factories (only if their tables are empty)...');
        $seededCount = 0;

        foreach ($this->discoverModelClasses() as $modelClass) {
            // Only attempt for Eloquent models with factory method
            if (! is_subclass_of($modelClass, Model::class)) {
                continue;
            }

            if (! method_exists($modelClass, 'factory')) {
                continue;
            }

            try {
                /** @var Model $model */
                $model = new $modelClass();

                // Skip if table already has data
                if ($model->newQuery()->exists()) {
                    continue;
                }

                // Default record count per empty table; adjust if needed later
                $countToCreate = 10;

                $created = $modelClass::factory()->count($countToCreate)->create();
                $this->command->info("✅ Seeded ".$created->count()." records for ".$modelClass);
                $seededCount++;
            } catch (\Throwable $e) {
                $this->command->warn('⚠️ Skipped '.$modelClass.' (reason: '.$e->getMessage().')');
            }
        }

        $this->command->info('🧮 Remaining empty models seeded: '.$seededCount);

        $this->command->info('🎉 Database seeding completed successfully!');
        $this->command->info('');
        $this->command->info('Demo users created:');
        $this->command->info('👤 Admin: admin@galileyo.com');
        $this->command->info('👤 Test: test@galileyo.com');
        $this->command->info('🌟 Influencer: influencer@galileyo.com');
        $this->command->info('');
        $this->command->info('All users have password: "password"');
    }

    /**
     * Discover all model classes under app/Models.
     *
     * @return array<int, class-string<\Illuminate\Database\Eloquent\Model>>
     */
    private function discoverModelClasses(): array
    {
        $baseDir = base_path('app/Models');
        if (! is_dir($baseDir)) {
            return [];
        }

        $classes = [];

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($baseDir, \FilesystemIterator::SKIP_DOTS)
        );

        /** @var \SplFileInfo $file */
        foreach ($iterator as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }

            $relativePath = str_replace($baseDir.'/', '', $file->getPathname());
            $relativeNoExt = substr($relativePath, 0, -4); // strip .php
            $fqcn = 'App\\Models\\'.str_replace(['/', '\\'], '\\', $relativeNoExt);

            if (class_exists($fqcn)) {
                $classes[] = $fqcn;
            }
        }

        // De-duplicate and keep order stable
        return array_values(array_unique($classes));
    }
}
