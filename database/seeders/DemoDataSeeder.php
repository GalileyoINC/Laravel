<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Communication\Contact;
use App\Models\Content\News;
use App\Models\Content\Page;
use App\Models\Device\Device;
use App\Models\Finance\Bundle;
use App\Models\Finance\Provider;
use App\Models\Finance\Service;
use App\Models\System\Setting;
use App\Models\System\Staff;
use App\Models\User\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create users
        $this->command->info('Creating users...');
        User::factory(50)->create();

        // Create staff members
        $this->command->info('Creating staff members...');
        Staff::factory(10)->create();
        Staff::factory(2)->admin()->create();
        Staff::factory(3)->regular()->create();

        // Create settings
        $this->command->info('Creating settings...');
        Setting::factory(20)->create();
        Setting::factory(5)->boolean()->create();
        Setting::factory(5)->numeric()->create();

        // Create providers
        $this->command->info('Creating providers...');
        Provider::factory(15)->create();
        Provider::factory(5)->satellite()->create();
        Provider::factory(5)->terrestrial()->create();

        // Create services
        $this->command->info('Creating services...');
        Service::factory(25)->create();

        // Create bundles
        $this->command->info('Creating bundles...');
        Bundle::factory(10)->create();
        Bundle::factory(3)->monthly()->create();
        Bundle::factory(2)->yearly()->create();
        Bundle::factory(5)->active()->create();

        // Create devices
        $this->command->info('Creating devices...');
        Device::factory(100)->create();

        // Create contacts
        $this->command->info('Creating contacts...');
        Contact::factory(30)->create();
        Contact::factory(10)->active()->create();
        Contact::factory(5)->inactive()->create();

        // Create news
        $this->command->info('Creating news...');
        News::factory(20)->create();

        // Create pages
        $this->command->info('Creating pages...');
        Page::factory(15)->create();
        Page::factory(5)->published()->create();
        Page::factory(3)->draft()->create();
        Page::factory(2)->landing()->create();

        $this->command->info('Demo data created successfully!');
        $this->command->info('Summary:');
        $this->command->info('- Users: 50');
        $this->command->info('- Staff: 15 (2 admin, 3 regular)');
        $this->command->info('- Settings: 30');
        $this->command->info('- Providers: 25 (5 satellite, 5 terrestrial)');
        $this->command->info('- Services: 25');
        $this->command->info('- Bundles: 20 (3 monthly, 2 yearly, 5 active)');
        $this->command->info('- Devices: 100');
        $this->command->info('- Contacts: 45 (10 active, 5 inactive)');
        $this->command->info('- News: 20');
        $this->command->info('- Pages: 20 (5 published, 3 draft, 2 landing)');
    }
}
