<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Notification\AppleNotification;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Notification\AppleNotification>
 */
class AppleNotificationFactory extends Factory
{
    protected $model = AppleNotification::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'notification_type' => $this->faker->randomElement(['INITIAL_BUY', 'DID_RENEW', 'DID_FAIL_TO_RENEW', 'DID_CHANGE_RENEWAL_PREF']),
            'transaction_info' => $this->faker->optional()->sentence(),
            'renewal_info' => $this->faker->optional()->sentence(),
            'payload' => $this->faker->optional()->randomElement(['key1' => 'value1', 'key2' => 'value2']),
            'data' => $this->faker->optional()->randomElement(['key1' => 'value1', 'key2' => 'value2']),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'transaction_id' => $this->faker->optional()->uuid(),
            'original_transaction_id' => $this->faker->optional()->uuid(),
            'is_process' => $this->faker->boolean(),
        ];
    }
}
