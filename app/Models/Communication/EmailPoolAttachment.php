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
 * Class EmailPoolAttachment
 *
 * @property int $id
 * @property int $id_email_pool
 * @property string $body
 * @property string $file_name
 * @property string|null $content_type
 * @property-read EmailPool $email_pool
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailPoolAttachment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailPoolAttachment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailPoolAttachment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailPoolAttachment whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailPoolAttachment whereContentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailPoolAttachment whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailPoolAttachment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailPoolAttachment whereIdEmailPool($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\CommunicationEmailPoolAttachmentFactory>
 */
class EmailPoolAttachment extends Model
{
    use HasFactory;

    /** @phpstan-ignore-line */
    public $timestamps = false;

    protected $table = 'email_pool_attachment';

    protected $casts = [
        'id_email_pool' => 'int',
    ];

    protected $fillable = [
        'id_email_pool',
        'body',
        'file_name',
        'content_type',
    ];

    /**
     * @return BelongsTo<EmailPool, $this>
     */
    public function email_pool(): BelongsTo
    {
        return $this->belongsTo(EmailPool::class, 'id_email_pool');
    }
}
