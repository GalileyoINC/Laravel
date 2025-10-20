<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Communication;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class ConversationMessage
 *
 * @property int $id
 * @property int|null $id_conversation
 * @property int|null $id_user
 * @property string|null $message
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $received_at
 * @property string|null $token
 * @property-read Conversation|null $conversation
 * @property-read Collection<int, ConversationFile> $conversation_files
 * @property-read int|null $conversation_files_count
 * @property-read Collection<int, ConversationUnviewed> $conversation_unvieweds
 * @property-read int|null $conversation_unvieweds_count
 * @property-read \App\Models\User\User|null $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConversationMessage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConversationMessage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConversationMessage query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConversationMessage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConversationMessage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConversationMessage whereIdConversation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConversationMessage whereIdUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConversationMessage whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConversationMessage whereReceivedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConversationMessage whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConversationMessage whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\CommunicationConversationMessageFactory>
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

    /**
     * @return BelongsTo<\App\Models\Communication\Conversation, $this>
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class, 'id_conversation');
    }

    /**
     * @return BelongsTo<\App\Models\User\User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User\User::class, 'id_user');
    }

    /**
     * @return HasMany<\App\Models\Communication\ConversationFile, $this>
     */
    public function conversation_files(): HasMany
    {
        return $this->hasMany(ConversationFile::class, 'id_conversation_message');
    }

    /**
     * @return HasMany<\App\Models\Communication\ConversationUnviewed, $this>
     */
    public function conversation_unvieweds(): HasMany
    {
        return $this->hasMany(ConversationUnviewed::class, 'id_conversation_message');
    }
}
