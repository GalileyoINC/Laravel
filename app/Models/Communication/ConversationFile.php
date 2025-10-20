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
 * Class ConversationFile
 *
 * @property int $id
 * @property int|null $id_conversation
 * @property int|null $id_conversation_message
 * @property string|null $folder_name
 * @property string|null $web_name
 * @property string|null $file_name
 * @property array<array-key, mixed>|null $sizes
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Conversation|null $conversation
 * @property-read ConversationMessage|null $conversation_message
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConversationFile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConversationFile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConversationFile query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConversationFile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConversationFile whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConversationFile whereFolderName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConversationFile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConversationFile whereIdConversation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConversationFile whereIdConversationMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConversationFile whereSizes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConversationFile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ConversationFile whereWebName($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\ConversationFileFactory>
 */
class ConversationFile extends Model
{
    use HasFactory;

    /** @phpstan-ignore-line */
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

    /**
     * @return BelongsTo<Conversation, $this>
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class, 'id_conversation');
    }

    /**
     * @return BelongsTo<ConversationMessage, $this>
     */
    public function conversation_message(): BelongsTo
    {
        return $this->belongsTo(ConversationMessage::class, 'id_conversation_message');
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): \Database\Factories\ConversationFileFactory
    {
        return \Database\Factories\ConversationFileFactory::new();
    }
}
