<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User\MemberRequest;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User\MemberRequest>
 */
class MemberRequestFactory extends Factory
{
    protected $model = MemberRequest::class;

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
