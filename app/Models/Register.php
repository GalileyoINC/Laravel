<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Register
 * 
 * @property int $id
 * @property string $email
 * @property string|null $first_name
 * @property string|null $last_name
 * @property int|null $is_unfinished_signup
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Register extends Model
{
	use HasFactory;

	protected $table = 'register';

	protected $casts = [
		'is_unfinished_signup' => 'int'
	];

	protected $fillable = [
		'email',
		'first_name',
		'last_name',
		'is_unfinished_signup'
	];
}
