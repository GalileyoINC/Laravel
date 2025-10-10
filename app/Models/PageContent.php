<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PageContent
 * 
 * @property int $id
 * @property int|null $id_page
 * @property int $status
 * @property array|null $params
 * @property string|null $content
 * @property Carbon $created_at
 * 
 * @property Page|null $page
 *
 * @package App\Models
 */
class PageContent extends Model
{
	protected $table = 'page_content';
	public $timestamps = false;

	protected $casts = [
		'id_page' => 'int',
		'status' => 'int',
		'params' => 'json'
	];

	protected $fillable = [
		'id_page',
		'status',
		'params',
		'content'
	];

	public function page()
	{
		return $this->belongsTo(Page::class, 'id_page');
	}
}
