<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Subscription;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class InfluencerAssistant
 *
 * @property int $id_influencer
 * @property int $id_assistant
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property User $user
 */
class InfluencerAssistant extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $table = 'influencer_assistant';

    protected $casts = [
        'id_influencer' => 'int',
        'id_assistant' => 'int',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User\User::class, 'id_influencer');
    }
}
