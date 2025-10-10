<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
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
 *
 * @package App\Models
 */
class Verification extends Model
{
	protected $table = 'verification';
	public $timestamps = false;

	protected $casts = [
		'expiresAt' => 'datetime',
		'createdAt' => 'datetime',
		'updatedAt' => 'datetime'
	];

	protected $fillable = [
		'identifier',
		'value',
		'expiresAt',
		'createdAt',
		'updatedAt'
	];
}
