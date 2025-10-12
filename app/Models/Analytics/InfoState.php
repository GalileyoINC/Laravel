<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Analytics;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class InfoState
 *
 * @property int $id
 * @property string $key
 * @property array|null $value
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 */
class InfoState extends Model
{
    use HasFactory;

    protected $table = 'info_state';

    protected $casts = [
        'value' => 'json',
    ];

    protected $fillable = [
        'key',
        'value',
    ];
}
