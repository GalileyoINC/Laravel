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
 * @property Carbon $createdAt
 * @property string|null $aaguid
 */
class Passkey extends Model
{
    use HasFactory;

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
}
