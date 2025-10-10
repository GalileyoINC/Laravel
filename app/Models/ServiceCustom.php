<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ServiceCustom
 * 
 * @property int $id
 * @property float $phone_price
 * @property float $feed_price
 * @property int $phone_min
 * @property int $phone_max
 * @property int $feed_min
 * @property int $feed_max
 *
 * @package App\Models
 */
class ServiceCustom extends Model
{
	use HasFactory;

	protected $table = 'service_custom';
	public $timestamps = false;

	protected $casts = [
		'phone_price' => 'float',
		'feed_price' => 'float',
		'phone_min' => 'int',
		'phone_max' => 'int',
		'feed_min' => 'int',
		'feed_max' => 'int'
	];

	protected $fillable = [
		'phone_price',
		'feed_price',
		'phone_min',
		'phone_max',
		'feed_min',
		'feed_max'
	];
}
