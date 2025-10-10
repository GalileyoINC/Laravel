<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
 *
 * @package App\Models
 */
class Passkey extends Model
{
	use HasFactory;

	protected $table = 'passkey';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'userId' => 'int',
		'counter' => 'int',
		'backedUp' => 'bool',
		'createdAt' => 'datetime'
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
		'aaguid'
	];
}
