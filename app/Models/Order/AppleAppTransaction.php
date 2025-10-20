<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AppleAppTransaction
 *
 * @property int $id
 * @property string|null $transaction_id
 * @property string|null $status
 * @property string|null $error
 * @property int|null $id_user
 * @property array<array-key, mixed>|null $data
 * @property \Illuminate\Support\Carbon|null $apple_created_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property bool|null $is_process
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppleAppTransaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppleAppTransaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppleAppTransaction query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppleAppTransaction whereAppleCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppleAppTransaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppleAppTransaction whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppleAppTransaction whereError($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppleAppTransaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppleAppTransaction whereIdUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppleAppTransaction whereIsProcess($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppleAppTransaction whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppleAppTransaction whereTransactionId($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\AppleAppTransactionFactory>
 */
class AppleAppTransaction extends Model
{
    use HasFactory;

    /** @phpstan-ignore-line */
    public $timestamps = false;

    protected $table = 'apple_app_transactions';

    protected $casts = [
        'id_user' => 'int',
        'data' => 'json',
        'apple_created_at' => 'datetime',
        'created_at' => 'datetime',
        'is_process' => 'bool',
    ];

    protected $fillable = [
        'transaction_id',
        'status',
        'error',
        'id_user',
        'data',
        'apple_created_at',
        'is_process',
    ];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): \Database\Factories\AppleAppTransactionFactory
    {
        return \Database\Factories\AppleAppTransactionFactory::new();
    }
}
