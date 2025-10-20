<?php

declare(strict_types=1);

namespace App\Models\Communication;

use App\Models\Subscription\FollowerList;
use App\Models\Subscription\Subscription;
use App\Models\System\Staff;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class SmsPoolArchive
 *
 * @property int $id
 * @property int $id_user
 * @property int $id_staff
 * @property int $id_subscription
 * @property int $id_follower_list
 * @property int $purpose
 * @property string $message
 * @property string $body
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 * @property-read FollowerList|null $followerList
 * @property-read Staff|null $staff
 * @property-read Subscription|null $subscription
 * @property-read User|null $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPoolArchive newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPoolArchive newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPoolArchive query()
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\SmsPoolArchiveFactory>
 */
class SmsPoolArchive extends Model
{
    use HasFactory;

    /** @phpstan-ignore-line */
    public int $id_user;

    public int $id_staff;

    public int $id_subscription;

    public int $id_follower_list;

    public int $purpose;

    public string $message;

    public string $body;

    public string $status;

    public string $created_at;

    public string $updated_at;

    public $timestamps = false;

    protected $table = 'sms_pool_archive';

    protected $casts = [
        'id_user' => 'int',
        'id_staff' => 'int',
        'id_subscription' => 'int',
        'id_follower_list' => 'int',
        'purpose' => 'int',
    ];

    protected $fillable = [
        'id_user',
        'id_staff',
        'id_subscription',
        'id_follower_list',
        'purpose',
        'message',
        'body',
        'status',
        'created_at',
        'updated_at',
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * @return BelongsTo<Staff, $this>
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'id_staff');
    }

    /**
     * @return BelongsTo<Subscription, $this>
     */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class, 'id_subscription');
    }

    /**
     * @return BelongsTo<FollowerList, $this>
     */
    public function followerList(): BelongsTo
    {
        return $this->belongsTo(FollowerList::class, 'id_follower_list');
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): \Database\Factories\SmsPoolArchiveFactory
    {
        return \Database\Factories\SmsPoolArchiveFactory::new();
    }
}
