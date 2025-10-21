<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User\UserPointHistory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User\UserPointHistory>
 */
class UserPointHistoryFactory extends Factory
{
    protected $model = UserPointHistory::class;

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
