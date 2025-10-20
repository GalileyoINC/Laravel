<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User\Invite;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User\Invite>
 */
class InviteFactory extends Factory
{
    protected $model = Invite::class;

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
