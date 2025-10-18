<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\User;

use Carbon\Carbon;
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
 * @property Carbon $created_at
 * @property Invoice|null $invoice
 * @property User $user
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
        return $this->belongsTo(\App\Models\User\User::class, 'id_inviter');
    }
}
