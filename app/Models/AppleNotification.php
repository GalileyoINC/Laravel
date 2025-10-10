<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class AppleNotification
 * 
 * @property int $id
 * @property string|null $notification_type
 * @property string|null $transaction_info
 * @property string|null $renewal_info
 * @property string|null $payload
 * @property string|null $data
 * @property Carbon $created_at
 * @property string|null $transaction_id
 * @property string|null $original_transaction_id
 * @property bool|null $is_process
 *
 * @package App\Models
 */
class AppleNotification extends Model
{
	use HasFactory;

	protected $table = 'apple_notification';
	public $timestamps = false;

	protected $casts = [
		'is_process' => 'bool'
	];

	protected $fillable = [
		'notification_type',
		'transaction_info',
		'renewal_info',
		'payload',
		'data',
		'transaction_id',
		'original_transaction_id',
		'is_process'
	];
}
