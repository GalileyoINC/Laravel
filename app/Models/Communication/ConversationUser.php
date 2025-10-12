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
 * @property Conversation $conversation
 * @property User $user
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
        return $this->belongsTo(App\Models\Communication\Conversation::class, 'id_conversation');
    }

    public function user()
    {
        return $this->belongsTo(App\Models\User\User::class, 'id_user');
    }
}
