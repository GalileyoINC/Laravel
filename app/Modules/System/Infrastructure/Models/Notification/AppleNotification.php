<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Modules\Communication\Infrastructure\Models\Notification;

use Carbon\Carbon;
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
 * @property Carbon $created_at
 * @property string|null $transaction_id
 * @property string|null $original_transaction_id
 * @property bool|null $is_process
 */
class AppleNotification extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'apple_notification';

    protected $casts = [
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
