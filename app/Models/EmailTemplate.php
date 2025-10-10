<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EmailTemplate
 * 
 * @property int $id
 * @property string $name
 * @property string|null $from
 * @property string $subject
 * @property string $body
 * @property string|null $bodyPlain
 * @property array|null $params
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class EmailTemplate extends Model
{
	protected $table = 'email_template';

	protected $casts = [
		'params' => 'json'
	];

	protected $fillable = [
		'name',
		'from',
		'subject',
		'body',
		'bodyPlain',
		'params'
	];
}
