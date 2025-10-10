<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class IexWebhook
 * 
 * @property int $id
 * @property string|null $iex_id
 * @property string|null $event
 * @property string|null $set
 * @property string|null $name
 * @property array|null $data
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class IexWebhook extends Model
{
	protected $table = 'iex_webhook';

	protected $casts = [
		'data' => 'json'
	];

	protected $fillable = [
		'iex_id',
		'event',
		'set',
		'name',
		'data'
	];
}
