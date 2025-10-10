<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DevicePlan
 * 
 * @property int $id
 * @property int|null $id_device
 * @property int|null $id_plan
 * @property bool $is_default
 * @property float|null $price
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Service|null $service
 *
 * @package App\Models
 */
class DevicePlan extends Model
{
	protected $table = 'device_plan';

	protected $casts = [
		'id_device' => 'int',
		'id_plan' => 'int',
		'is_default' => 'bool',
		'price' => 'float'
	];

	protected $fillable = [
		'id_device',
		'id_plan',
		'is_default',
		'price'
	];

	public function service()
	{
		return $this->belongsTo(Service::class, 'id_plan');
	}
}
