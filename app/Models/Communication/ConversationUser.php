<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Communication;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
class ConversationUser extends Model
{
    use HasFactory;

    public $incrementing = false;

    public $timestamps = false;

    protected $table = 'conversation_user';

    protected $casts = [
        'id_conversation' => 'int',
        'id_user' => 'int',
    ];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class, 'id_conversation');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User\User::class, 'id_user');
    }
}
