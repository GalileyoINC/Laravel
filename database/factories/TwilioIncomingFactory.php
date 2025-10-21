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
        return [
            //
        ];
    }
}
