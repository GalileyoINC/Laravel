<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TwilioIncoming
 * 
 * @property int $id
 * @property string|null $number
 * @property string|null $body
 * @property array|null $message
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property int|null $type
 *
 * @package App\Models
 */
class TwilioIncoming extends Model
{
	protected $table = 'twilio_incoming';

	protected $casts = [
		'message' => 'json',
		'type' => 'int'
	];

	protected $fillable = [
		'number',
		'body',
		'message',
		'type'
	];
}
