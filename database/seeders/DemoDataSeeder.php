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
        // Create users only if there are less than 50
        $this->command->info('Creating users...');
        $existingUsers = User::count();
        if ($existingUsers < 50) {
            $usersToCreate = 50 - $existingUsers;
            User::factory($usersToCreate)->create();
            $this->command->info("✅ Created {$usersToCreate} additional users");
        } else {
            $this->command->info("⏭️  Skipped: Already have {$existingUsers} users");
        }

        // Create staff members
        $this->command->info('Creating staff members...');
        Staff::factory(10)->create();
        Staff::factory(2)->admin()->create();
        Staff::factory(3)->regular()->create();

        // Create settings
        $this->command->info('Creating settings...');
        $existingSettings = Setting::count();
        if ($existingSettings < 20) {
            $settingsToCreate = 20 - $existingSettings;
            Setting::factory($settingsToCreate)->create();
            $this->command->info("✅ Created {$settingsToCreate} additional settings");
        } else {
            $this->command->info("⏭️  Skipped: Already have {$existingSettings} settings");
        }

        if ($existingSettings < 25) {
            $booleanSettingsToCreate = min(5, 25 - $existingSettings);
            Setting::factory($booleanSettingsToCreate)->boolean()->create();
            $this->command->info("✅ Created {$booleanSettingsToCreate} boolean settings");
        }

        if ($existingSettings < 30) {
            $numericSettingsToCreate = min(5, 30 - $existingSettings);
            Setting::factory($numericSettingsToCreate)->numeric()->create();
            $this->command->info("✅ Created {$numericSettingsToCreate} numeric settings");
        }

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
