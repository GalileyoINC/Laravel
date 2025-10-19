<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Subscription;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BpSubscription
 *
 * @property int $id
 * @property int $id_user
 * @property string $id_subscription
 * @property string $id_bill
 * @property int $status
 * @property string $email
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Collection<int, \App\Models\Finance\Invoice> $invoices
 * @property-read int|null $invoices_count
 * @property-read \App\Models\User\User $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BpSubscription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BpSubscription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BpSubscription query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BpSubscription whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BpSubscription whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BpSubscription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BpSubscription whereIdBill($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BpSubscription whereIdSubscription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BpSubscription whereIdUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BpSubscription whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BpSubscription whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class BpSubscription extends Model
{
    use HasFactory;

    protected $table = 'bp_subscription';

    protected $casts = [
        'id_user' => 'int',
        'status' => 'int',
    ];

    protected $fillable = [
        'id_user',
        'id_subscription',
        'id_bill',
        'status',
        'email',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User\User::class, 'id_user');
    }

    public function invoices()
    {
        return $this->hasMany(\App\Models\Finance\Invoice::class, 'id_bp_subscribe');
    }
}
