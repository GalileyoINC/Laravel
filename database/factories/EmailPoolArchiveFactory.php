<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Communication\EmailPoolArchive;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Communication\EmailPoolArchive>
 */
class EmailPoolArchiveFactory extends Factory
{
    protected $model = EmailPoolArchive::class;

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
