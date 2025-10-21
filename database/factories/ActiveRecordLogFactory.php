<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\System\ActiveRecordLog;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\System\ActiveRecordLog>
 */
class ActiveRecordLogFactory extends Factory
{
    protected $model = ActiveRecordLog::class;

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
