<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class UserService
 *
 * @property int|null $id_user
 * @property int|null $id_service
 * @property-read \App\Models\Finance\Service|null $service
 * @property-read User|null $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserService newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserService newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserService query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserService whereIdService($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserService whereIdUser($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\UserUserServiceFactory>
 */
class UserService extends Model
{
    use HasFactory;

    /** @phpstan-ignore-line */
    public $incrementing = false;

    public $timestamps = false;

    protected $table = 'user_service';

    protected $casts = [
        'id_user' => 'int',
        'id_service' => 'int',
    ];

    protected $fillable = [
        'id_user',
        'id_service',
    ];

    /**
     * @return BelongsTo<\App\Models\Finance\Service, $this>
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Finance\Service::class, 'id_service');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
