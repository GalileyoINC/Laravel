<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BpSubscription
 * 
 * @property int $id
 * @property int $id_user
 * @property string $id_subscription
 * @property string $id_bill
 * @property int $status
 * @property string $email
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User $user
 * @property Collection|Invoice[] $invoices
 *
 * @package App\Models
 */
class BpSubscription extends Model
{
	protected $table = 'bp_subscription';

	protected $casts = [
		'id_user' => 'int',
		'status' => 'int'
	];

	protected $fillable = [
		'id_user',
		'id_subscription',
		'id_bill',
		'status',
		'email'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'id_user');
	}

	public function invoices()
	{
		return $this->hasMany(Invoice::class, 'id_bp_subscribe');
	}
}
