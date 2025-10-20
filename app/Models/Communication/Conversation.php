<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Communication;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Conversation
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Collection<int, ConversationFile> $conversation_files
 * @property-read int|null $conversation_files_count
 * @property-read Collection<int, ConversationMessage> $conversation_messages
 * @property-read int|null $conversation_messages_count
 * @property-read Collection<int, ConversationUnviewed> $conversation_unvieweds
 * @property-read int|null $conversation_unvieweds_count
 * @property-read Collection<int, \App\Models\User\User> $users
 * @property-read int|null $users_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Conversation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Conversation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Conversation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Conversation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Conversation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Conversation whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\CommunicationConversationFactory>
 */
class Conversation extends Model
{
    use HasFactory;

    protected $table = 'conversation';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Communication\ConversationFile, $this>
     */
    public function conversation_files(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ConversationFile::class, 'id_conversation');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Communication\ConversationMessage, $this>
     */
    public function conversation_messages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ConversationMessage::class, 'id_conversation');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Communication\ConversationUnviewed, $this>
     */
    public function conversation_unvieweds(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ConversationUnviewed::class, 'id_conversation');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<\App\Models\User\User, $this>
     */
    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(\App\Models\User\User::class, 'conversation_user', 'id_conversation', 'id_user');
    }
}
