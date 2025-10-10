<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AuthItemChild
 * 
 * @property string $parent
 * @property string $child
 * 
 * @property AuthItem $auth_item
 *
 * @package App\Models
 */
class AuthItemChild extends Model
{
	protected $table = 'auth_item_child';
	public $incrementing = false;
	public $timestamps = false;

	public function auth_item()
	{
		return $this->belongsTo(AuthItem::class, 'child');
	}
}
