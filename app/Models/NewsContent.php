<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class NewsContent
 * 
 * @property int $id
 * @property int|null $id_news
 * @property int $status
 * @property array|null $params
 * @property string|null $content
 * @property Carbon $created_at
 * 
 * @property News|null $news
 *
 * @package App\Models
 */
class NewsContent extends Model
{
	use HasFactory;

	protected $table = 'news_content';
	public $timestamps = false;

	protected $casts = [
		'id_news' => 'int',
		'status' => 'int',
		'params' => 'json'
	];

	protected $fillable = [
		'id_news',
		'status',
		'params',
		'content'
	];

	public function news()
	{
		return $this->belongsTo(News::class, 'id_news');
	}
}
