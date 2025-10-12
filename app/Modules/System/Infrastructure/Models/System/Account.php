<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\System;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Account
 *
 * @property int $id
 * @property string $accountId
 * @property string $providerId
 * @property int $userId
 * @property string|null $accessToken
 * @property string|null $refreshToken
 * @property string|null $idToken
 * @property Carbon|null $accessTokenExpiresAt
 * @property Carbon|null $refreshTokenExpiresAt
 * @property string|null $scope
 * @property string|null $password
 * @property Carbon $createdAt
 * @property Carbon $updatedAt
 */
class Account extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'account';

    protected $casts = [
        'userId' => 'int',
        'accessTokenExpiresAt' => 'datetime',
        'refreshTokenExpiresAt' => 'datetime',
        'createdAt' => 'datetime',
        'updatedAt' => 'datetime',
    ];

    protected $hidden = [
        'password',
    ];

    protected $fillable = [
        'accountId',
        'providerId',
        'userId',
        'accessToken',
        'refreshToken',
        'idToken',
        'accessTokenExpiresAt',
        'refreshTokenExpiresAt',
        'scope',
        'password',
        'createdAt',
        'updatedAt',
    ];
}
