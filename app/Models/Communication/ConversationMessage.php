<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Communication;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ConversationMessage
 *
 * @property int $id
 * @property int|null $id_conversation
 * @property int|null $id_user
 * @property string|null $message
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $received_at
 * @property string|null $token
 * @property Conversation|null $conversation
 * @property User|null $user
 * @property Collection|ConversationFile[] $conversation_files
 * @property Collection|ConversationUnviewed[] $conversation_unvieweds
 */
class ConversationMessage extends Model
{
    use HasFactory;

    protected $table = 'conversation_message';

    protected $casts = [
        'id_conversation' => 'int',
        'id_user' => 'int',
        'received_at' => 'datetime',
    ];

    protected $hidden = [
        'token',
    ];

    protected $fillable = [
        'id_conversation',
        'id_user',
        'message',
        'received_at',
        'token',
    ];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class, 'id_conversation');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User\User::class, 'id_user');
    }

    public function conversation_files()
    {
        return $this->hasMany(ConversationFile::class, 'id_conversation_message');
    }

    public function conversation_unvieweds()
    {
        return $this->hasMany(ConversationUnviewed::class, 'id_conversation_message');
    }
}
