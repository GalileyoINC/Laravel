<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class EmailPoolAttachment
 * 
 * @property int $id
 * @property int $id_email_pool
 * @property string $body
 * @property string $file_name
 * @property string|null $content_type
 * 
 * @property EmailPool $email_pool
 *
 * @package App\Models
 */
class EmailPoolAttachment extends Model
{
	use HasFactory;

	protected $table = 'email_pool_attachment';
	public $timestamps = false;

	protected $casts = [
		'id_email_pool' => 'int'
	];

	protected $fillable = [
		'id_email_pool',
		'body',
		'file_name',
		'content_type'
	];

	public function email_pool()
	{
		return $this->belongsTo(EmailPool::class, 'id_email_pool');
	}
}
