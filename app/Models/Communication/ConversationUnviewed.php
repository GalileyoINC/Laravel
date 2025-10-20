<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Communication;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class ConversationUnviewed
 *
 * @property int $id
 * @property int|null $id_user
 * @property int|null $id_conversation
 * @property int|null $id_conversation_message
 * @property-read Conversation|null $conversation
 * @property-read ConversationMessage|null $conversation_message
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConversationUnviewed newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConversationUnviewed newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConversationUnviewed query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConversationUnviewed whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConversationUnviewed whereIdConversation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConversationUnviewed whereIdConversationMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConversationUnviewed whereIdUser($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\CommunicationConversationUnviewedFactory>
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

    /**
     * @return BelongsTo<\App\Models\Communication\Conversation, $this>
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class, 'id_conversation');
    }

    /**
     * @return BelongsTo<\App\Models\Communication\ConversationMessage, $this>
     */
    public function conversation_message(): BelongsTo
    {
        return $this->belongsTo(ConversationMessage::class, 'id_conversation_message');
    }
}
