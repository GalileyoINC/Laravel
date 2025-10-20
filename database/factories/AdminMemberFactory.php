<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User\AdminMember;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User\AdminMember>
 */
class AdminMemberFactory extends Factory
{
    protected $model = AdminMember::class;

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
