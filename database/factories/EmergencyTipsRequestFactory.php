<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User\EmergencyTipsRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User\EmergencyTipsRequest>
 */
class EmergencyTipsRequestFactory extends Factory
{
    protected $model = EmergencyTipsRequest::class;

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
