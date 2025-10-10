<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
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
 *
 * @package App\Models
 */
class Account extends Model
{
	protected $table = 'account';
	public $timestamps = false;

	protected $casts = [
		'userId' => 'int',
		'accessTokenExpiresAt' => 'datetime',
		'refreshTokenExpiresAt' => 'datetime',
		'createdAt' => 'datetime',
		'updatedAt' => 'datetime'
	];

	protected $hidden = [
		'password'
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
		'updatedAt'
	];
}
