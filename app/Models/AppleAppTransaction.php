<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class AppleAppTransaction
 * 
 * @property int $id
 * @property string|null $transaction_id
 * @property string|null $status
 * @property string|null $error
 * @property int|null $id_user
 * @property array|null $data
 * @property Carbon|null $apple_created_at
 * @property Carbon $created_at
 * @property bool|null $is_process
 *
 * @package App\Models
 */
class AppleAppTransaction extends Model
{
	use HasFactory;

	protected $table = 'apple_app_transactions';
	public $timestamps = false;

	protected $casts = [
		'id_user' => 'int',
		'data' => 'json',
		'apple_created_at' => 'datetime',
		'is_process' => 'bool'
	];

	protected $fillable = [
		'transaction_id',
		'status',
		'error',
		'id_user',
		'data',
		'apple_created_at',
		'is_process'
	];
}
