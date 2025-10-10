<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SmsPoolPhoto
 * 
 * @property int $id
 * @property int|null $id_sms_pool
 * @property int|null $id_sms_shedule
 * @property string|null $folder_name
 * @property string|null $web_name
 * @property array|null $sizes
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property string|null $uuid
 * 
 * @property SmsPool|null $sms_pool
 * @property SmsShedule|null $sms_shedule
 *
 * @package App\Models
 */
class SmsPoolPhoto extends Model
{
	protected $table = 'sms_pool_photo';

	protected $casts = [
		'id_sms_pool' => 'int',
		'id_sms_shedule' => 'int',
		'sizes' => 'json'
	];

	protected $fillable = [
		'id_sms_pool',
		'id_sms_shedule',
		'folder_name',
		'web_name',
		'sizes',
		'uuid'
	];

	public function sms_pool()
	{
		return $this->belongsTo(SmsPool::class, 'id_sms_pool');
	}

	public function sms_shedule()
	{
		return $this->belongsTo(SmsShedule::class, 'id_sms_shedule');
	}
}
