<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User\UserPlanShedule;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User\UserPlanShedule>
 */
class UserPlanSheduleFactory extends Factory
{
    protected $model = UserPlanShedule::class;

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
