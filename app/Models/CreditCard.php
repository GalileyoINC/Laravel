<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CreditCard
 * 
 * @property int $id
 * @property int|null $id_user
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $num
 * @property string|null $cvv
 * @property string|null $type
 * @property int|null $expiration_year
 * @property int|null $expiration_month
 * @property bool $is_active
 * @property int $is_preferred
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property string|null $anet_customer_payment_profile_id
 * @property int $anet_profile_deleted
 * @property string|null $phone
 * @property string|null $zip
 * @property bool $is_agree_to_receive
 * @property array|null $billing_address
 * 
 * @property User|null $user
 * @property Collection|MoneyTransaction[] $money_transactions
 *
 * @package App\Models
 */
class CreditCard extends Model
{
	protected $table = 'credit_card';

	protected $casts = [
		'id_user' => 'int',
		'expiration_year' => 'int',
		'expiration_month' => 'int',
		'is_active' => 'bool',
		'is_preferred' => 'int',
		'anet_profile_deleted' => 'int',
		'is_agree_to_receive' => 'bool',
		'billing_address' => 'json'
	];

	protected $fillable = [
		'id_user',
		'first_name',
		'last_name',
		'num',
		'cvv',
		'type',
		'expiration_year',
		'expiration_month',
		'is_active',
		'is_preferred',
		'anet_customer_payment_profile_id',
		'anet_profile_deleted',
		'phone',
		'zip',
		'is_agree_to_receive',
		'billing_address'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'id_user');
	}

	public function money_transactions()
	{
		return $this->hasMany(MoneyTransaction::class, 'id_credit_card');
	}
}
