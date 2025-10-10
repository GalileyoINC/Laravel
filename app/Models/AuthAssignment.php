<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class AuthAssignment
 * 
 * @property string $item_name
 * @property string $user_id
 * @property int|null $created_at
 * 
 * @property AuthItem $auth_item
 *
 * @package App\Models
 */
class AuthAssignment extends Model
{
	use HasFactory;

	protected $table = 'auth_assignment';
	public $incrementing = false;
	public $timestamps = false;

	public function auth_item()
	{
		return $this->belongsTo(AuthItem::class, 'item_name');
	}
}
