<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Analytics;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class LogAuthorize
 *
 * @property int $id
 * @property int|null $id_user
 * @property int|null $id_money_transaction
 * @property string|null $name
 * @property array<array-key, mixed>|null $request
 * @property array<array-key, mixed>|null $response
 * @property int|null $status
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Finance\MoneyTransaction|null $money_transaction
 * @property-read \App\Models\User\User|null $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogAuthorize newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogAuthorize newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogAuthorize query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogAuthorize whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogAuthorize whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogAuthorize whereIdMoneyTransaction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogAuthorize whereIdUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogAuthorize whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogAuthorize whereRequest($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogAuthorize whereResponse($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogAuthorize whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogAuthorize whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 * @method static \Database\Factories\LogAuthorizeFactory factory()
 */
/**
 * @phpstan-use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\LogAuthorizeFactory>
 */
/**
 * @phpstan-ignore-next-line
 */
class LogAuthorize extends Model
{
    /** @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\LogAuthorizeFactory> */
    use HasFactory;

    /** @phpstan-ignore-line */
    protected $table = 'log_authorize';

    protected $casts = [
        'id_user' => 'int',
        'id_money_transaction' => 'int',
        'request' => 'json',
        'response' => 'json',
        'status' => 'int',
    ];

    protected $fillable = [
        'id_user',
        'id_money_transaction',
        'name',
        'request',
        'response',
        'status',
    ];

    /**
     * @return BelongsTo<\App\Models\Finance\MoneyTransaction, $this>
     */
    public function money_transaction(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Finance\MoneyTransaction::class, 'id_money_transaction');
    }

    /**
     * @return BelongsTo<\App\Models\User\User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User\User::class, 'id_user');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Factories\Factory<LogAuthorize>
     */
    protected static function newFactory(): \Illuminate\Database\Eloquent\Factories\Factory
    {
        return \Database\Factories\LogAuthorizeFactory::new();
    }
}
