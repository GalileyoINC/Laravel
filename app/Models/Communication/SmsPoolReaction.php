<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Communication;

use Carbon\Carbon;
use Database\Factories\SmsPoolReactionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SmsPoolReaction
 *
 * @property int $id_sms_pool
 * @property int $id_user
 * @property int|null $id_reaction
 * @property Carbon $created_at
 * @property Reaction|null $reaction
 * @property SmsPool $sms_pool
 * @property User $user
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
        return $this->belongsTo(App\Models\Communication\SmsPool::class, 'id_sms_pool');
    }

    public function user()
    {
        return $this->belongsTo(App\Models\User\User::class, 'id_user');
    }

    protected static function newFactory()
    {
        return SmsPoolReactionFactory::new();
    }
}
