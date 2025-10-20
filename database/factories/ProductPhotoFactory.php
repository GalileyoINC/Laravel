<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Content\ProductPhoto;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Content\ProductPhoto>
 */
class ProductPhotoFactory extends Factory
{
    protected $model = ProductPhoto::class;

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
