<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class SpsContract
 * 
 * @property int $id
 * @property int $id_user
 * @property int $id_contract
 * @property int|null $id_plan
 * @property int $id_service
 * @property int $alert
 * @property int $max_phone_cnt
 * @property int $pay_interval
 * @property Carbon $begin_at
 * @property Carbon $ended_at
 * @property Carbon|null $terminated_at
 * @property int|null $is_secondary
 * @property array|null $user_plan_data
 * 
 * @property UserPlan|null $user_plan
 * @property Service $service
 * @property User $user
 * @property Collection|ContractLine[] $contract_lines
 *
 * @package App\Models
 */
class SpsContract extends Model
{
	use HasFactory;

	protected $table = 'sps_contract';
	public $timestamps = false;

	protected $casts = [
		'id_user' => 'int',
		'id_contract' => 'int',
		'id_plan' => 'int',
		'id_service' => 'int',
		'alert' => 'int',
		'max_phone_cnt' => 'int',
		'pay_interval' => 'int',
		'begin_at' => 'datetime',
		'ended_at' => 'datetime',
		'terminated_at' => 'datetime',
		'is_secondary' => 'int',
		'user_plan_data' => 'json'
	];

	protected $fillable = [
		'id_user',
		'id_contract',
		'id_plan',
		'id_service',
		'alert',
		'max_phone_cnt',
		'pay_interval',
		'begin_at',
		'ended_at',
		'terminated_at',
		'is_secondary',
		'user_plan_data'
	];

	public function user_plan()
	{
		return $this->belongsTo(UserPlan::class, 'id_plan');
	}

	public function service()
	{
		return $this->belongsTo(Service::class, 'id_service');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'id_user');
	}

	public function contract_lines()
	{
		return $this->hasMany(ContractLine::class, 'id_sps_contract');
	}
}
