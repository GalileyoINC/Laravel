<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class AffiliateInvite
 * 
 * @property int $id
 * @property int $id_user
 * @property string $email
 * @property string|null $phone_number
 * @property string|null $token
 * @property array|null $params
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User $user
 *
 * @package App\Models
 */
class AffiliateInvite extends Model
{
	use HasFactory;

	protected $table = 'affiliate_invite';

	protected $casts = [
		'id_user' => 'int',
		'params' => 'json'
	];

	protected $hidden = [
		'token'
	];

	protected $fillable = [
		'id_user',
		'email',
		'phone_number',
		'token',
		'params'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'id_user');
	}
}
