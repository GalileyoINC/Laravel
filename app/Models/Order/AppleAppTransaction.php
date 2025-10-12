<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Order;

use Carbon\Carbon;
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
 * @property array|null $data
 * @property Carbon|null $apple_created_at
 * @property Carbon $created_at
 * @property bool|null $is_process
 */
class AppleAppTransaction extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'apple_app_transactions';

    protected $casts = [
        'id_user' => 'int',
        'data' => 'json',
        'apple_created_at' => 'datetime',
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
}
