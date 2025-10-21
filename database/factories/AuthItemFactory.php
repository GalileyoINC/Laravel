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
            'name' => $this->faker->unique()->word() . '_' . $this->faker->randomNumber(3),
            'type' => $this->faker->randomElement([1, 2]), // 1 = Role, 2 = Permission
            'description' => $this->faker->optional()->sentence(),
            'rule_name' => null, // Set to null to avoid foreign key constraint
            'data' => null, // Set to null to avoid JSON issues
            'created_at' => time(),
            'updated_at' => time(),
        ];
    }

    /**
     * Configure the model factory.
     */
    public function configure(): static
    {
        return $this;
    }
}
