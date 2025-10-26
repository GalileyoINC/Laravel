<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\System\IexWebhook;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\System\IexWebhook>
 */
class IexWebhookFactory extends Factory
{
    protected $model = IexWebhook::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'iex_id' => fake()->uuid(),
            'event' => fake()->randomElement(['subscription_started', 'subscription_renewed', 'subscription_cancelled', 'subscription_expired']),
            'set' => fake()->randomElement(['NYSE', 'NASDAQ', 'AMS', 'TSE']),
            'name' => fake()->company().' Webhook',
            'data' => [
                'symbol' => fake()->randomElement(['AAPL', 'GOOGL', 'MSFT', 'AMZN']),
                'timestamp' => now()->toIso8601String(),
                'price' => fake()->randomFloat(2, 100, 500),
            ],
        ];
    }
}
