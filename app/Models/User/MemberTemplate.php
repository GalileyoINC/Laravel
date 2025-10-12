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
 * Class MemberTemplate
 *
 * @property int $id
 * @property int|null $id_admin
 * @property string|null $first_name
 * @property string|null $last_name
 * @property int|null $id_plan
 * @property string|null $email
 * @property string|null $member_key
 * @property Carbon|null $expired_at
 * @property UserPlan|null $user_plan
 * @property User|null $user
 */
class MemberTemplate extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'member_template';

    protected $casts = [
        'id_admin' => 'int',
        'id_plan' => 'int',
        'expired_at' => 'datetime',
    ];

    protected $fillable = [
        'id_admin',
        'first_name',
        'last_name',
        'id_plan',
        'email',
        'member_key',
        'expired_at',
    ];

    public function user_plan()
    {
        return $this->belongsTo(App\Models\User\UserPlan::class, 'id_plan');
    }

    public function user()
    {
        return $this->belongsTo(App\Models\User\User::class, 'id_admin');
    }
}
