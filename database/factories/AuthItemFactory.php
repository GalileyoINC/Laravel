<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User\AuthItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User\AuthItem>
 */
class AuthItemFactory extends Factory
{
    protected $model = AuthItem::class;

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
