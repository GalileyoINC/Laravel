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
 * Class Conversation
 * 
 * @property int $id
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|ConversationFile[] $conversation_files
 * @property Collection|ConversationMessage[] $conversation_messages
 * @property Collection|ConversationUnviewed[] $conversation_unvieweds
 * @property Collection|User[] $users
 *
 * @package App\Models
 */
class Conversation extends Model
{
	use HasFactory;

	protected $table = 'conversation';

	public function conversation_files()
	{
		return $this->hasMany(ConversationFile::class, 'id_conversation');
	}

	public function conversation_messages()
	{
		return $this->hasMany(ConversationMessage::class, 'id_conversation');
	}

	public function conversation_unvieweds()
	{
		return $this->hasMany(ConversationUnviewed::class, 'id_conversation');
	}

	public function users()
	{
		return $this->belongsToMany(User::class, 'conversation_user', 'id_conversation', 'id_user');
	}
}
