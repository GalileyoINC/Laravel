<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\System;

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
 * @property \Illuminate\Support\Carbon|null $accessTokenExpiresAt
 * @property \Illuminate\Support\Carbon|null $refreshTokenExpiresAt
 * @property string|null $scope
 * @property string|null $password
 * @property \Illuminate\Support\Carbon $createdAt
 * @property \Illuminate\Support\Carbon $updatedAt
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereAccessToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereAccessTokenExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereIdToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereProviderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereRefreshToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereRefreshTokenExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereScope($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereUserId($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\AccountFactory>
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
