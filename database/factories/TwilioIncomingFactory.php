<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\System\TwilioIncoming;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\System\TwilioIncoming>
 */
class TwilioIncomingFactory extends Factory
{
    protected $model = TwilioIncoming::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $phoneNumber = $this->faker->e164PhoneNumber();
        $body = $this->faker->sentence();

        return [
            'number' => $phoneNumber,
            'body' => $body,
            'message' => [
                'From' => $phoneNumber,
                'To' => '+1234567890',
                'Body' => $body,
                'MessageSid' => 'SM'.$this->faker->uuid(),
                'AccountSid' => 'AC'.$this->faker->uuid(),
            ],
            'type' => $this->faker->numberBetween(0, 3),
        ];
    }
}
