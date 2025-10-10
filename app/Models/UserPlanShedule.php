<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class UserPlanShedule
 * 
 * @property int $id
 * @property int|null $id_user
 * @property int|null $id_service
 * @property array|null $settings
 * @property Carbon|null $begin_at
 * @property Carbon|null $end_at
 * @property bool $is_complete
 * @property bool $is_new_custom
 * @property int|null $id_contract_line
 * 
 * @property ContractLine|null $contract_line
 * @property Service|null $service
 * @property User|null $user
 *
 * @package App\Models
 */
class UserPlanShedule extends Model
{
	use HasFactory;

	protected $table = 'user_plan_shedule';
	public $timestamps = false;

	protected $casts = [
		'id_user' => 'int',
		'id_service' => 'int',
		'settings' => 'json',
		'begin_at' => 'datetime',
		'end_at' => 'datetime',
		'is_complete' => 'bool',
		'is_new_custom' => 'bool',
		'id_contract_line' => 'int'
	];

	protected $fillable = [
		'id_user',
		'id_service',
		'settings',
		'begin_at',
		'end_at',
		'is_complete',
		'is_new_custom',
		'id_contract_line'
	];

	public function contract_line()
	{
		return $this->belongsTo(ContractLine::class, 'id_contract_line');
	}

	public function service()
	{
		return $this->belongsTo(Service::class, 'id_service');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'id_user');
	}
}
