<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Device\PhoneNumber;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Device\PhoneNumber>
 */
class PhoneNumberFactory extends Factory
{
    protected $model = PhoneNumber::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_user' => $this->faker->numberBetween(1, 100), // Use existing user IDs
            'id_provider' => $this->faker->optional()->numberBetween(1, 25),
            'type' => $this->faker->optional()->numberBetween(1, 3),
            'is_satellite' => $this->faker->boolean(20), // 20% satellite
            'number' => $this->faker->e164PhoneNumber(),
            'is_active' => $this->faker->boolean(80), // 80% active
            'is_primary' => $this->faker->boolean(10), // 10% primary
            'is_send' => $this->faker->boolean(70), // 70% can send
            'is_emergency_only' => $this->faker->boolean(5), // 5% emergency only
            'is_valid' => $this->faker->boolean(90), // 90% valid
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->optional()->dateTimeBetween('-1 year', 'now'),
            'twilio_type' => $this->faker->optional()->randomElement(['mobile', 'landline', 'voip']),
            'twilio_type_raw' => json_encode($this->faker->optional()->randomElements(['carrier' => $this->faker->company(), 'type' => 'mobile'])),
            'numverify_type' => $this->faker->optional()->randomElement(['mobile', 'landline', 'voip']),
            'numverify_raw' => json_encode($this->faker->optional()->randomElements(['valid' => true, 'carrier' => $this->faker->company()])),
            'bivy_status' => json_encode($this->faker->optional()->randomElements(['status' => 'active', 'last_check' => $this->faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d H:i:s')])),
        ];
    }
}
