<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ActiveRecordLog
 * 
 * @property int $id
 * @property int|null $id_user
 * @property int|null $id_staff
 * @property int|null $action_type
 * @property string|null $model
 * @property string|null $id_model
 * @property string|null $field
 * @property array|null $changes
 * @property Carbon $created_at
 *
 * @package App\Models
 */
class ActiveRecordLog extends Model
{
	use HasFactory;

	protected $table = 'active_record_log';
	public $timestamps = false;

	protected $casts = [
		'id_user' => 'int',
		'id_staff' => 'int',
		'action_type' => 'int',
		'changes' => 'json'
	];

	protected $fillable = [
		'id_user',
		'id_staff',
		'action_type',
		'model',
		'id_model',
		'field',
		'changes'
	];
}
