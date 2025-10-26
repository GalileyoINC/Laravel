<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\ProductDigitalAlerts;
use Illuminate\Database\Seeder;

class AlertMapSampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Skip if alerts already exist
        if (ProductDigitalAlerts::count() > 0) {
            $this->command->info('⏭️  Skipped: Alert map data already exists');

            return;
        }

        $this->command->info('Creating alert map sample data...');

        $sampleAlerts = [
            [
                'type' => 1,
                'status' => 'active',
                'title' => 'Severe Weather Warning',
                'description' => 'Heavy rainfall and strong winds expected in the area. Please stay indoors and avoid unnecessary travel.',
                'alert_data' => json_encode(['weather_type' => 'storm', 'wind_speed' => '45 mph']),
                'latitude' => 40.7128,
                'longitude' => -74.006,
                'address' => 'New York, NY 10001',
                'severity' => 'high',
                'category' => 'weather',
                'affected_radius' => 10.0,
                'source' => 'National Weather Service',
                'expires_at' => now()->addHours(6),
            ],
            [
                'type' => 2,
                'status' => 1,
                'title' => 'Traffic Accident on Highway',
                'description' => 'Multi-vehicle accident reported on I-95 North. Expect delays and use alternate routes.',
                'alert_data' => json_encode(['accident_type' => 'multi_vehicle', 'injuries' => 'minor']),
                'latitude' => 40.7589,
                'longitude' => -73.9857,
                'address' => 'I-95 North, Bronx, NY',
                'severity' => 'medium',
                'category' => 'traffic',
                'affected_radius' => 5.0,
                'source' => 'NYC Traffic Control',
                'expires_at' => now()->addHours(3),
            ],
            [
                'type' => 3,
                'status' => 1,
                'title' => 'Fire Emergency Response',
                'description' => 'Fire department responding to building fire. Avoid the area and follow emergency personnel instructions.',
                'alert_data' => json_encode(['fire_type' => 'building', 'response_units' => 3]),
                'latitude' => 40.7489,
                'longitude' => -73.9957,
                'address' => '123 Main Street, Brooklyn, NY',
                'severity' => 'critical',
                'category' => 'fire',
                'affected_radius' => 2.0,
                'source' => 'FDNY',
                'expires_at' => now()->addHours(2),
            ],
            [
                'type' => 4,
                'status' => 1,
                'title' => 'Medical Emergency',
                'description' => 'Medical emergency in progress. Emergency services are responding.',
                'alert_data' => json_encode(['medical_type' => 'cardiac', 'ambulance_dispatched' => true]),
                'latitude' => 40.7289,
                'longitude' => -73.9857,
                'address' => 'Central Park, New York, NY',
                'severity' => 'high',
                'category' => 'medical',
                'affected_radius' => 1.0,
                'source' => 'NYC EMS',
                'expires_at' => now()->addHours(1),
            ],
            [
                'type' => 5,
                'status' => 1,
                'title' => 'Security Alert',
                'description' => 'Suspicious activity reported in the area. Increased police presence expected.',
                'alert_data' => json_encode(['security_type' => 'suspicious_activity', 'police_units' => 2]),
                'latitude' => 40.7189,
                'longitude' => -73.9757,
                'address' => 'Times Square, New York, NY',
                'severity' => 'medium',
                'category' => 'security',
                'affected_radius' => 3.0,
                'source' => 'NYPD',
                'expires_at' => now()->addHours(4),
            ],
            [
                'type' => 6,
                'status' => 1,
                'title' => 'Power Outage',
                'description' => 'Scheduled power maintenance causing temporary outages in the area.',
                'alert_data' => json_encode(['outage_type' => 'scheduled', 'estimated_duration' => '2 hours']),
                'latitude' => 40.7089,
                'longitude' => -73.9657,
                'address' => 'Financial District, New York, NY',
                'severity' => 'low',
                'category' => 'utility',
                'affected_radius' => 8.0,
                'source' => 'Con Edison',
                'expires_at' => now()->addHours(2),
            ],
            [
                'type' => 7,
                'status' => 1,
                'title' => 'Road Construction',
                'description' => 'Major road construction project causing lane closures and delays.',
                'alert_data' => json_encode(['construction_type' => 'road_repair', 'lanes_closed' => 2]),
                'latitude' => 40.6989,
                'longitude' => -73.9557,
                'address' => 'Brooklyn Bridge, New York, NY',
                'severity' => 'low',
                'category' => 'construction',
                'affected_radius' => 4.0,
                'source' => 'NYC DOT',
                'expires_at' => now()->addDays(7),
            ],
            [
                'type' => 8,
                'status' => 1,
                'title' => 'Emergency Evacuation',
                'description' => 'Emergency evacuation in progress due to gas leak. Please evacuate immediately.',
                'alert_data' => json_encode(['evacuation_type' => 'gas_leak', 'evacuation_radius' => '500m']),
                'latitude' => 40.6889,
                'longitude' => -73.9457,
                'address' => 'Queens, NY',
                'severity' => 'critical',
                'category' => 'emergency',
                'affected_radius' => 0.5,
                'source' => 'NYC Emergency Management',
                'expires_at' => now()->addHours(1),
            ],
        ];

        // Create additional alerts with random coordinates across different areas
        $additionalAlerts = [];
        for ($i = 0; $i < 50; $i++) {
            $types = [1, 2, 3, 4, 5, 6, 7, 8];
            $severities = ['critical', 'high', 'medium', 'low'];
            $categories = ['weather', 'traffic', 'security', 'medical', 'fire', 'police', 'construction', 'emergency', 'utility'];

            // Random coordinates across US (varied locations)
            $lat = fake()->latitude(25.0, 49.0); // US latitude range
            $lng = fake()->longitude(-125.0, -66.0); // US longitude range

            $additionalAlerts[] = [
                'type' => fake()->randomElement($types),
                'status' => 'active',
                'title' => fake()->sentence(3),
                'description' => fake()->paragraph(2),
                'alert_data' => json_encode([
                    'source' => fake()->company,
                    'confidence' => fake()->numberBetween(1, 100),
                    'affected_area' => fake()->city,
                ]),
                'latitude' => $lat,
                'longitude' => $lng,
                'address' => fake()->address,
                'severity' => fake()->randomElement($severities),
                'category' => fake()->randomElement($categories),
                'affected_radius' => fake()->numberBetween(100, 5000),
                'source' => fake()->company,
                'expires_at' => fake()->dateTimeBetween('now', '+1 week'),
            ];
        }

        // Combine sample and additional alerts
        $allAlerts = array_merge($sampleAlerts, $additionalAlerts);

        foreach ($allAlerts as $alert) {
            ProductDigitalAlerts::create($alert);
        }

        $this->command->info('✅ Sample alert map data created successfully! (Total: '.count($allAlerts).' alerts)');
    }
}
