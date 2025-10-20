<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Passkey
 *
 * @property string $id
 * @property string|null $name
 * @property string $publicKey
 * @property int $userId
 * @property string $credentialID
 * @property int $counter
 * @property string $deviceType
 * @property bool $backedUp
 * @property string $transports
 * @property \Illuminate\Support\Carbon $createdAt
 * @property string|null $aaguid
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Passkey newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Passkey newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Passkey query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Passkey whereAaguid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Passkey whereBackedUp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Passkey whereCounter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Passkey whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Passkey whereCredentialID($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Passkey whereDeviceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Passkey whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Passkey whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Passkey wherePublicKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Passkey whereTransports($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Passkey whereUserId($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\PasskeyFactory>
 */
class Passkey extends Model
{
    use HasFactory;

    /** @phpstan-ignore-line */
    public $incrementing = false;

    public $timestamps = false;

    protected $table = 'passkey';

    protected $casts = [
        'userId' => 'int',
        'counter' => 'int',
        'backedUp' => 'bool',
        'createdAt' => 'datetime',
    ];

    protected $fillable = [
        'name',
        'publicKey',
        'userId',
        'credentialID',
        'counter',
        'deviceType',
        'backedUp',
        'transports',
        'createdAt',
        'aaguid',
    ];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): \Database\Factories\PasskeyFactory
    {
        return \Database\Factories\PasskeyFactory::new();
    }
}
