<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Communication;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ConversationUnviewed
 *
 * @property int $id
 * @property int|null $id_user
 * @property int|null $id_conversation
 * @property int|null $id_conversation_message
 * @property Conversation|null $conversation
 * @property ConversationMessage|null $conversation_message
 */
class ConversationUnviewed extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'conversation_unviewed';

    protected $casts = [
        'id_user' => 'int',
        'id_conversation' => 'int',
        'id_conversation_message' => 'int',
    ];

    protected $fillable = [
        'id_user',
        'id_conversation',
        'id_conversation_message',
    ];

    public function conversation()
    {
        return $this->belongsTo(\App\Models\Communication\Conversation::class, 'id_conversation');
    }

    public function conversation_message()
    {
        return $this->belongsTo(\App\Models\Communication\ConversationMessage::class, 'id_conversation_message');
    }
}
