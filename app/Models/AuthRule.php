<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AuthRule
 * 
 * @property string $name
 * @property string|null $data
 * @property int|null $created_at
 * @property int|null $updated_at
 * 
 * @property Collection|AuthItem[] $auth_items
 *
 * @package App\Models
 */
class AuthRule extends Model
{
	protected $table = 'auth_rule';
	protected $primaryKey = 'name';
	public $incrementing = false;

	protected $fillable = [
		'data'
	];

	public function auth_items()
	{
		return $this->hasMany(AuthItem::class, 'rule_name');
	}
}
