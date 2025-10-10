<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ConversationUser
 * 
 * @property int $id_conversation
 * @property int $id_user
 * 
 * @property Conversation $conversation
 * @property User $user
 *
 * @package App\Models
 */
class ConversationUser extends Model
{
	use HasFactory;

	protected $table = 'conversation_user';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id_conversation' => 'int',
		'id_user' => 'int'
	];

	public function conversation()
	{
		return $this->belongsTo(Conversation::class, 'id_conversation');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'id_user');
	}
}
