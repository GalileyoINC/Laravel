<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User\Passkey;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User\Passkey>
 */
class PasskeyFactory extends Factory
{
    protected $model = Passkey::class;

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
