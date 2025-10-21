<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User\AffiliateInvite;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User\AffiliateInvite>
 */
class AffiliateInviteFactory extends Factory
{
    protected $model = AffiliateInvite::class;

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
