<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProductPhoto
 * 
 * @property int $id
 * @property int|null $id_service
 * @property string|null $folder_name
 * @property array|null $sizes
 * @property bool|null $is_main
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Service|null $service
 *
 * @package App\Models
 */
class ProductPhoto extends Model
{
	protected $table = 'product_photo';

	protected $casts = [
		'id_service' => 'int',
		'sizes' => 'json',
		'is_main' => 'bool'
	];

	protected $fillable = [
		'id_service',
		'folder_name',
		'sizes',
		'is_main'
	];

	public function service()
	{
		return $this->belongsTo(Service::class, 'id_service');
	}
}
