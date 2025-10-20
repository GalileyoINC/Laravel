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
 * Class ConversationUser
 *
 * @property int $id_conversation
 * @property int $id_user
 * @property-read Conversation $conversation
 * @property-read \App\Models\User\User $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConversationUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConversationUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConversationUser query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConversationUser whereIdConversation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConversationUser whereIdUser($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\ConversationUserFactory>
 */
class ConversationUser extends Model
{
    use HasFactory;

    /** @phpstan-ignore-line */
    public $incrementing = false;

    public $timestamps = false;

    protected $table = 'conversation_user';

    protected $casts = [
        'id_conversation' => 'int',
        'id_user' => 'int',
    ];

    /**
     * @return BelongsTo<Conversation, $this>
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
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): \Database\Factories\ConversationUserFactory
    {
        return \Database\Factories\ConversationUserFactory::new();
    }
}
