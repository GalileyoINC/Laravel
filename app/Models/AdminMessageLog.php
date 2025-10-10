<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class AdminMessageLog
 * 
 * @property int $id
 * @property int $id_staff
 * @property int|null $obj_type
 * @property string|null $obj_id
 * @property string|null $body
 * @property Carbon|null $created_at
 *
 * @package App\Models
 */
class AdminMessageLog extends Model
{
	use HasFactory;

	protected $table = 'admin_message_log';
	public $timestamps = false;

	protected $casts = [
		'id_staff' => 'int',
		'obj_type' => 'int'
	];

	protected $fillable = [
		'id_staff',
		'obj_type',
		'obj_id',
		'body'
	];
}
