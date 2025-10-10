<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class InfoState
 * 
 * @property int $id
 * @property string $key
 * @property array|null $value
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class InfoState extends Model
{
	use HasFactory;

	protected $table = 'info_state';

	protected $casts = [
		'value' => 'json'
	];

	protected $fillable = [
		'key',
		'value'
	];
}
