<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Subscription;

use Carbon\Carbon;
use Database\Factories\FollowerListFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class FollowerList
 *
 * @property int $id
 * @property int $id_user
 * @property string $name
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property string|null $token
 * @property string|null $description
 * @property string|null $image
 * @property bool $is_active
 * @property User $user
 * @property Collection|Follower[] $followers
 * @property Collection|Invite[] $invites
 * @property Collection|SmsShedule[] $sms_shedules
 */
class FollowerList extends Model
{
    use HasFactory;

    protected $table = 'follower_list';

    protected $casts = [
        'id_user' => 'int',
        'is_active' => 'bool',
    ];

    protected $hidden = [
        'token',
    ];

    protected $fillable = [
        'id_user',
        'name',
        'token',
        'description',
        'image',
        'is_active',
    ];

    public function user()
    {
        return $this->belongsTo(App\Models\User\User::class, 'id_user');
    }

    public function followers()
    {
        return $this->hasMany(App\Models\Subscription\Follower::class, 'id_follower_list');
    }

    public function invites()
    {
        return $this->hasMany(App\Models\User\Invite::class, 'id_follower_list');
    }

    public function sms_shedules()
    {
        return $this->hasMany(App\Models\Communication\SmsShedule::class, 'id_follower_list');
    }

    protected static function newFactory()
    {
        return FollowerListFactory::new();
    }
}
