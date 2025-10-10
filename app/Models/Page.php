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
 * Class Page
 * 
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int $status
 * @property array|null $params
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|PageContent[] $page_contents
 *
 * @package App\Models
 */
class Page extends Model
{
	use HasFactory;

	protected $table = 'page';

	protected $casts = [
		'status' => 'int',
		'params' => 'json'
	];

	protected $fillable = [
		'name',
		'slug',
		'status',
		'params'
	];

	public function page_contents()
	{
		return $this->hasMany(PageContent::class, 'id_page');
	}
}
