<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Notification;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AppleNotification
 *
 * @property int $id
 * @property string|null $notification_type
 * @property string|null $transaction_info
 * @property string|null $renewal_info
 * @property string|null $payload
 * @property string|null $data
 * @property \Illuminate\Support\Carbon $created_at
 * @property string|null $transaction_id
 * @property string|null $original_transaction_id
 * @property bool|null $is_process
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppleNotification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppleNotification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppleNotification query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppleNotification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppleNotification whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppleNotification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppleNotification whereIsProcess($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppleNotification whereNotificationType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppleNotification whereOriginalTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppleNotification wherePayload($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppleNotification whereRenewalInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppleNotification whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppleNotification whereTransactionInfo($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\AppleNotificationFactory>
 */
class AppleNotification extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'apple_notification';

    protected $casts = [
        'created_at' => 'datetime',
        'is_process' => 'bool',
    ];

    protected $fillable = [
        'notification_type',
        'transaction_info',
        'renewal_info',
        'payload',
        'data',
        'transaction_id',
        'original_transaction_id',
        'is_process',
    ];
}
