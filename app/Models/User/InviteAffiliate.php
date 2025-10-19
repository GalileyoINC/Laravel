<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class InviteAffiliate
 *
 * @property int $id
 * @property int $id_inviter
 * @property int $id_invited
 * @property int $id_invite_invoice
 * @property int|null $id_reward_invoice
 * @property string $created_at
 * @property-read \App\Models\Finance\Invoice|null $invoice
 * @property-read User $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InviteAffiliate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InviteAffiliate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InviteAffiliate query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InviteAffiliate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InviteAffiliate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InviteAffiliate whereIdInviteInvoice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InviteAffiliate whereIdInvited($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InviteAffiliate whereIdInviter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InviteAffiliate whereIdRewardInvoice($value)
 *
 * @mixin \Eloquent
 */
class InviteAffiliate extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'invite_affiliate';

    protected $casts = [
        'id_inviter' => 'int',
        'id_invited' => 'int',
        'id_invite_invoice' => 'int',
        'id_reward_invoice' => 'int',
    ];

    protected $fillable = [
        'id_inviter',
        'id_invited',
        'id_invite_invoice',
        'id_reward_invoice',
    ];

    public function invoice()
    {
        return $this->belongsTo(\App\Models\Finance\Invoice::class, 'id_reward_invoice');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_inviter');
    }
}
