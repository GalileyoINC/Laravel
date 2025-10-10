<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class ConversationUnviewed
 * 
 * @property int $id
 * @property int|null $id_user
 * @property int|null $id_conversation
 * @property int|null $id_conversation_message
 * 
 * @property Conversation|null $conversation
 * @property ConversationMessage|null $conversation_message
 *
 * @package App\Models
 */
class ConversationUnviewed extends Model
{
	use HasFactory;

	protected $table = 'conversation_unviewed';
	public $timestamps = false;

	protected $casts = [
		'id_user' => 'int',
		'id_conversation' => 'int',
		'id_conversation_message' => 'int'
	];

	protected $fillable = [
		'id_user',
		'id_conversation',
		'id_conversation_message'
	];

	public function conversation()
	{
		return $this->belongsTo(Conversation::class, 'id_conversation');
	}

	public function conversation_message()
	{
		return $this->belongsTo(ConversationMessage::class, 'id_conversation_message');
	}
}
