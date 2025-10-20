<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\System;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ApiLog
 *
 * @property int $id
 * @property string $key
 * @property string|null $value
 * @property \Illuminate\Support\Carbon $created_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiLog whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApiLog whereValue($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\ApiLogFactory>
 */
class ApiLog extends Model
{
    use HasFactory;

    /** @phpstan-ignore-line */
    public $timestamps = false;

    protected $table = 'api_log';

    protected $casts = [
        'created_at' => 'datetime',
    ];

    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): \Database\Factories\ApiLogFactory
    {
        return \Database\Factories\ApiLogFactory::new();
    }
}
