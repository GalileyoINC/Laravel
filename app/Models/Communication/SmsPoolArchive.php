<?php

declare(strict_types=1);

namespace App\Models\Communication;

use App\Models\Subscription\FollowerList;
use App\Models\Subscription\Subscription;
use App\Models\User\User;
use App\Models\System\Staff;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SmsPoolArchive extends Model
{
    use HasFactory;

    protected $table = 'sms_pool_archive';

    public $timestamps = false;

    protected $casts = [
        'id_user' => 'int',
        'id_staff' => 'int',
        'id_subscription' => 'int',
        'id_follower_list' => 'int',
    ];

    protected $fillable = [
        'id_user',
        'id_staff',
        'id_subscription',
        'id_follower_list',
        'message',
        'status',
        'created_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'id_staff');
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class, 'id_subscription');
    }

    public function followerList(): BelongsTo
    {
        return $this->belongsTo(FollowerList::class, 'id_follower_list');
    }
}
