<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Verification
 *
 * @property int $id
 * @property string $identifier
 * @property string $value
 * @property \Illuminate\Support\Carbon $expiresAt
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Verification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Verification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Verification query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Verification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Verification whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Verification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Verification whereIdentifier($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Verification whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Verification whereValue($value)
 *
 * @mixin \Eloquent
 */
class Verification extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'verification';

    protected $casts = [
        'expiresAt' => 'datetime',
        'createdAt' => 'datetime',
        'updatedAt' => 'datetime',
    ];

    protected $fillable = [
        'identifier',
        'value',
        'expiresAt',
        'createdAt',
        'updatedAt',
    ];
}
