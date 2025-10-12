<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Modules\User\Infrastructure\Models\User;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Verification
 *
 * @property int $id
 * @property string $identifier
 * @property string $value
 * @property Carbon $expiresAt
 * @property Carbon|null $createdAt
 * @property Carbon|null $updatedAt
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
