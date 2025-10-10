<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Podcast
 * 
 * @property int $id
 * @property int $type
 * @property string|null $title
 * @property string|null $url
 * @property string|null $image
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Podcast extends Model
{
	use HasFactory;

	protected $table = 'podcast';

	protected $casts = [
		'type' => 'int'
	];

	protected $fillable = [
		'type',
		'title',
		'url',
		'image'
	];
}
