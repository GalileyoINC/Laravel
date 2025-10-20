<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Analytics;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class InfoState
 *
 * @property int $id
 * @property string $key
 * @property array<array-key, mixed>|null $value
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InfoState newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InfoState newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InfoState query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InfoState whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InfoState whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InfoState whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InfoState whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InfoState whereValue($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\AnalyticsInfoStateFactory>
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
