<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Communication;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ConversationFile
 *
 * @property int $id
 * @property int|null $id_conversation
 * @property int|null $id_conversation_message
 * @property string|null $folder_name
 * @property string|null $web_name
 * @property string|null $file_name
 * @property array|null $sizes
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property Conversation|null $conversation
 * @property ConversationMessage|null $conversation_message
 */
class ConversationFile extends Model
{
    use HasFactory;

    protected $table = 'conversation_file';

    protected $casts = [
        'id_conversation' => 'int',
        'id_conversation_message' => 'int',
        'sizes' => 'json',
    ];

    protected $fillable = [
        'id_conversation',
        'id_conversation_message',
        'folder_name',
        'web_name',
        'file_name',
        'sizes',
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
