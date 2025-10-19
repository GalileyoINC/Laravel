<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Communication;

use Database\Factories\SmsPoolReactionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SmsPoolReaction
 *
 * @property int $id_sms_pool
 * @property int $id_user
 * @property int|null $id_reaction
 * @property string $created_at
 * @property-read SmsPool $sms_pool
 * @property-read \App\Models\User\User $user
 *
 * @method static \Database\Factories\SmsPoolReactionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPoolReaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPoolReaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPoolReaction query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPoolReaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPoolReaction whereIdReaction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPoolReaction whereIdSmsPool($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPoolReaction whereIdUser($value)
 *
 * @mixin \Eloquent
 */
class SmsPoolReaction extends Model
{
    use HasFactory;

    public $incrementing = false;

    public $timestamps = false;

    protected $table = 'sms_pool_reaction';

    protected $primaryKey = ['id_sms_pool', 'id_user'];

    protected $casts = [
        'id_sms_pool' => 'int',
        'id_user' => 'int',
        'id_reaction' => 'int',
    ];

    protected $fillable = [
        'id_sms_pool',
        'id_user',
        'id_reaction',
        'created_at',
    ];

    public function reaction()
    {
        return $this->belongsTo(Reaction::class, 'id_reaction');
    }

    public function sms_pool()
    {
        return $this->belongsTo(SmsPool::class, 'id_sms_pool');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User\User::class, 'id_user');
    }

    protected static function newFactory()
    {
        return SmsPoolReactionFactory::new();
    }
}
