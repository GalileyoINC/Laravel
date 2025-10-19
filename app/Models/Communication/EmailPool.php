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
 * Class EmailPool
 *
 * @property int $id
 * @property int $status
 * @property int $type
 * @property string|null $to
 * @property string|null $from
 * @property string|null $reply
 * @property string|null $bcc
 * @property string|null $subject
 * @property string|null $body
 * @property string|null $bodyPlain
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Collection<int, EmailPoolAttachment> $attachments
 * @property-read int|null $attachments_count
 * @property-read Collection<int, EmailPoolAttachment> $email_pool_attachments
 * @property-read int|null $email_pool_attachments_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailPool newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailPool newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailPool query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailPool whereBcc($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailPool whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailPool whereBodyPlain($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailPool whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailPool whereFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailPool whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailPool whereReply($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailPool whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailPool whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailPool whereTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailPool whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailPool whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class EmailPool extends Model
{
    use HasFactory;

    /**
     * Status constants
     */
    public const STATUS_PENDING = 0;

    public const STATUS_SENT = 1;

    public const STATUS_FAILED = 2;

    public const STATUS_CANCELLED = 3;

    protected $table = 'email_pool';

    protected $casts = [
        'status' => 'int',
        'type' => 'int',
    ];

    protected $fillable = [
        'status',
        'type',
        'to',
        'from',
        'reply',
        'bcc',
        'subject',
        'body',
        'bodyPlain',
    ];

    /**
     * Get available sending types for dropdowns
     */
    public static function getSendingTypes(): array
    {
        return [
            0 => 'General',
            1 => 'Marketing',
            2 => 'Notification',
            3 => 'System',
        ];
    }

    /**
     * Get available statuses for dropdowns
     */
    public static function getStatuses(): array
    {
        return [
            0 => 'Pending',
            1 => 'Sent',
            2 => 'Failed',
            3 => 'Cancelled',
        ];
    }

    public function email_pool_attachments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(EmailPoolAttachment::class, 'id_email_pool');
    }

    public function attachments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(EmailPoolAttachment::class, 'id_email_pool');
    }
}
