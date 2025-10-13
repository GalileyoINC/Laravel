<?php

declare(strict_types=1);

namespace Database\Factories\Device;

use App\Models\Device\Device;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Device>
 */
class DeviceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Device::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $osTypes = ['iOS', 'Android', 'Windows', 'macOS', 'Linux'];
        $os = fake()->randomElement($osTypes);

        return [
            'id_user' => User::factory(),
            'uuid' => Str::uuid(),
            'os' => $os,
            'push_token' => fake()->boolean(80) ? Str::random(64) : null,
            'access_token' => Str::random(32),
            'params' => json_encode([
                'device_model' => fake()->randomElement(['iPhone 15', 'Samsung Galaxy S24', 'Google Pixel 8', 'iPad Pro', 'MacBook Pro']),
                'os_version' => fake()->randomElement(['17.0', '14.0', '11.0', '13.0', 'Ubuntu 22.04']),
                'app_version' => fake()->randomElement(['1.0.0', '1.2.3', '2.0.1', '1.5.0']),
                'screen_resolution' => fake()->randomElement(['1920x1080', '2560x1440', '375x812', '414x896']),
                'timezone' => fake()->timezone(),
                'language' => fake()->randomElement(['en', 'es', 'fr', 'de', 'it']),
            ]),
            'push_turn_on' => fake()->boolean(70), // 70% chance
            'created_at' => fake()->dateTimeBetween('-1 year', 'now'),
            'updated_at' => fake()->dateTimeBetween('-6 months', 'now'),
        ];
    }

    /**
     * Indicate that the device is iOS.
     */
    public function ios(): static
    {
        return $this->state(fn (array $attributes) => [
            'os' => 'iOS',
            'params' => json_encode([
                'device_model' => fake()->randomElement(['iPhone 15', 'iPhone 14', 'iPhone 13', 'iPad Pro', 'iPad Air']),
                'os_version' => fake()->randomElement(['17.0', '16.0', '15.0']),
                'app_version' => fake()->randomElement(['1.0.0', '1.2.3', '2.0.1']),
                'screen_resolution' => fake()->randomElement(['375x812', '414x896', '390x844', '428x926']),
                'timezone' => fake()->timezone(),
                'language' => fake()->randomElement(['en', 'es', 'fr', 'de', 'it']),
            ]),
        ]);
    }

    /**
     * Indicate that the device is Android.
     */
    public function android(): static
    {
        return $this->state(fn (array $attributes) => [
            'os' => 'Android',
            'params' => json_encode([
                'device_model' => fake()->randomElement(['Samsung Galaxy S24', 'Google Pixel 8', 'OnePlus 12', 'Xiaomi 14']),
                'os_version' => fake()->randomElement(['14.0', '13.0', '12.0']),
                'app_version' => fake()->randomElement(['1.0.0', '1.2.3', '2.0.1']),
                'screen_resolution' => fake()->randomElement(['1920x1080', '2560x1440', '2340x1080']),
                'timezone' => fake()->timezone(),
                'language' => fake()->randomElement(['en', 'es', 'fr', 'de', 'it']),
            ]),
        ]);
    }

    /**
     * Indicate that push notifications are enabled.
     */
    public function pushEnabled(): static
    {
        return $this->state(fn (array $attributes) => [
            'push_turn_on' => true,
            'push_token' => Str::random(64),
        ]);
    }

    /**
     * Indicate that push notifications are disabled.
     */
    public function pushDisabled(): static
    {
        return $this->state(fn (array $attributes) => [
            'push_turn_on' => false,
            'push_token' => null,
        ]);
    }

    /**
     * Indicate that the device is recent.
     */
    public function recent(): static
    {
        return $this->state(fn (array $attributes) => [
            'created_at' => fake()->dateTimeBetween('-1 month', 'now'),
            'updated_at' => fake()->dateTimeBetween('-1 week', 'now'),
        ]);
    }
}
