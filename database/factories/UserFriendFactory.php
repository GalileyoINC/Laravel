<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User\UserFriend;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User\UserFriend>
 */
class UserFriendFactory extends Factory
{
    protected $model = UserFriend::class;

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
