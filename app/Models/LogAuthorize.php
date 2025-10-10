<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class LogAuthorize
 * 
 * @property int $id
 * @property int|null $id_user
 * @property int|null $id_money_transaction
 * @property string|null $name
 * @property array|null $request
 * @property array|null $response
 * @property int|null $status
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * 
 * @property MoneyTransaction|null $money_transaction
 * @property User|null $user
 *
 * @package App\Models
 */
class LogAuthorize extends Model
{
	use HasFactory;

	protected $table = 'log_authorize';

	protected $casts = [
		'id_user' => 'int',
		'id_money_transaction' => 'int',
		'request' => 'json',
		'response' => 'json',
		'status' => 'int'
	];

	protected $fillable = [
		'id_user',
		'id_money_transaction',
		'name',
		'request',
		'response',
		'status'
	];

	public function money_transaction()
	{
		return $this->belongsTo(MoneyTransaction::class, 'id_money_transaction');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'id_user');
	}
}
