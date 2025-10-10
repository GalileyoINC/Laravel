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
 * Class News
 * 
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $image
 * @property int $status
 * @property array|null $params
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|NewsContent[] $news_contents
 *
 * @package App\Models
 */
class News extends Model
{
	use HasFactory;

	protected $table = 'news';

	protected $casts = [
		'status' => 'int',
		'params' => 'json'
	];

	protected $fillable = [
		'name',
		'slug',
		'image',
		'status',
		'params'
	];

	public function news_contents()
	{
		return $this->hasMany(NewsContent::class, 'id_news');
	}
}
