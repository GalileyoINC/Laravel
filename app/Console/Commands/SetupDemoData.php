<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class SetupDemoData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demo:setup {--fresh : Drop all tables and re-run migrations}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set up demo data for the Galileyo Laravel application';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Setting up Galileyo Laravel Demo Data...');
        $this->newLine();

        // Check if fresh option is used
        if ($this->option('fresh')) {
            $this->info('🔄 Fresh setup requested - dropping all tables...');
            Artisan::call('migrate:fresh');
            $this->info('✅ Database refreshed');
        } else {
            $this->info('🔄 Running migrations...');
            Artisan::call('migrate');
            $this->info('✅ Migrations completed');
        }

        $this->newLine();

        // Run database seeding
        $this->info('🌱 Seeding database with demo data...');
        Artisan::call('db:seed');
        $this->info('✅ Database seeded successfully');

        $this->newLine();

        // Display summary
        $this->displaySummary();

        $this->newLine();
        $this->info('🎉 Demo data setup completed successfully!');
        $this->newLine();

        $this->info('📋 Next steps:');
        $this->line('1. Start the Laravel server: php artisan serve');
        $this->line('2. Test the API endpoints using the demo users');
        $this->line('3. Check the database for generated data');

        $this->newLine();
        $this->info('🔗 API Base URL: http://localhost:8000/api');
        $this->info('📊 Database: Check your MySQL database for demo data');
    }

    /**
     * Display summary of created data
     */
    private function displaySummary()
    {
        $this->info('📊 Demo Data Summary:');
        $this->newLine();

        try {
            $userCount = DB::table('user')->count();
            $subscriptionCount = DB::table('subscription')->count();
            $newsCount = DB::table('news')->count();
            $commentCount = DB::table('comment')->count();
            $creditCardCount = DB::table('credit_card')->count();
            $deviceCount = DB::table('device')->count();
            $smsPoolCount = DB::table('sms_pool')->count();
            $followerListCount = DB::table('follower_list')->count();
            $influencerPageCount = DB::table('influencer_page')->count();

            $this->table(
                ['Model', 'Count'],
                [
                    ['Users', $userCount],
                    ['Subscriptions', $subscriptionCount],
                    ['News', $newsCount],
                    ['Comments', $commentCount],
                    ['Credit Cards', $creditCardCount],
                    ['Devices', $deviceCount],
                    ['SMS Pools', $smsPoolCount],
                    ['Follower Lists', $followerListCount],
                    ['Influencer Pages', $influencerPageCount],
                ]
            );

            $this->newLine();
            $this->info('👤 Demo Users Created:');
            $this->line('• Admin: admin@galileyo.com (password: password)');
            $this->line('• Test: test@galileyo.com (password: password)');
            $this->line('• Influencer: influencer@galileyo.com (password: password)');

        } catch (Exception $e) {
            $this->warn('Could not retrieve data counts: '.$e->getMessage());
        }
    }
}
