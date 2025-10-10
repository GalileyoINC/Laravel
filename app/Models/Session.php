<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Session
 * 
 * @property int $id
 * @property Carbon $expiresAt
 * @property string $token
 * @property Carbon $createdAt
 * @property Carbon $updatedAt
 * @property string|null $ipAddress
 * @property string|null $userAgent
 * @property int $userId
 *
 * @package App\Models
 */
class Session extends Model
{
	protected $table = 'session';
	public $timestamps = false;

	protected $casts = [
		'expiresAt' => 'datetime',
		'createdAt' => 'datetime',
		'updatedAt' => 'datetime',
		'userId' => 'int'
	];

	protected $hidden = [
		'token'
	];

	protected $fillable = [
		'expiresAt',
		'token',
		'createdAt',
		'updatedAt',
		'ipAddress',
		'userAgent',
		'userId'
	];
}
