<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Contact
 * 
 * @property int $id
 * @property int|null $id_user
 * @property string $name
 * @property string $email
 * @property string|null $subject
 * @property string $body
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property int $status
 * 
 * @property User|null $user
 *
 * @package App\Models
 */
class Contact extends Model
{
	use HasFactory;

	protected $table = 'contact';

	protected $casts = [
		'id_user' => 'int',
		'status' => 'int'
	];

	protected $fillable = [
		'id_user',
		'name',
		'email',
		'subject',
		'body',
		'status'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'id_user');
	}
}
