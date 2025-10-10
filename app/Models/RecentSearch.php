<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RecentSearch
 * 
 * @property int $id
 * @property int $id_user
 * @property string|null $phrase
 * @property int|null $id_search_user
 *
 * @package App\Models
 */
class RecentSearch extends Model
{
	protected $table = 'recent_search';
	public $timestamps = false;

	protected $casts = [
		'id_user' => 'int',
		'id_search_user' => 'int'
	];

	protected $fillable = [
		'id_user',
		'phrase',
		'id_search_user'
	];
}
