<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class EmailPool
 * 
 * @property int $id
 * @property int $status
 * @property int $type
 * @property string|null $to
 * @property string|null $from
 * @property string|null $reply
 * @property string|null $bcc
 * @property string|null $subject
 * @property string|null $body
 * @property string|null $bodyPlain
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|EmailPoolAttachment[] $email_pool_attachments
 *
 * @package App\Models
 */
class EmailPool extends Model
{
	use HasFactory;

	protected $table = 'email_pool';

	protected $casts = [
		'status' => 'int',
		'type' => 'int'
	];

	protected $fillable = [
		'status',
		'type',
		'to',
		'from',
		'reply',
		'bcc',
		'subject',
		'body',
		'bodyPlain'
	];

	public function email_pool_attachments()
	{
		return $this->hasMany(EmailPoolAttachment::class, 'id_email_pool');
	}
}
