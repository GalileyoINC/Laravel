<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class BundleItem
 * 
 * @property int $id
 * @property int|null $id_bundle
 * @property int|null $id_item
 * @property int|null $type
 * @property float $price
 * @property int|null $quantity
 * @property array|null $custom_data
 * 
 * @property Bundle|null $bundle
 * @property Service|null $service
 *
 * @package App\Models
 */
class BundleItem extends Model
{
	use HasFactory;

	protected $table = 'bundle_item';
	public $timestamps = false;

	protected $casts = [
		'id_bundle' => 'int',
		'id_item' => 'int',
		'type' => 'int',
		'price' => 'float',
		'quantity' => 'int',
		'custom_data' => 'json'
	];

	protected $fillable = [
		'id_bundle',
		'id_item',
		'type',
		'price',
		'quantity',
		'custom_data'
	];

	public function bundle()
	{
		return $this->belongsTo(Bundle::class, 'id_bundle');
	}

	public function service()
	{
		return $this->belongsTo(Service::class, 'id_item');
	}
}
