<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AdminMember
 *
 * @property int $id
 * @property int|null $id_admin
 * @property int|null $id_member
 * @property int|null $id_plan
 * @property User|null $user
 * @property UserPlan|null $user_plan
 */
class AdminMember extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'admin_member';

    protected $casts = [
        'id_admin' => 'int',
        'id_member' => 'int',
        'id_plan' => 'int',
    ];

    protected $fillable = [
        'id_admin',
        'id_member',
        'id_plan',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User\User::class, 'id_member');
    }

    public function user_plan()
    {
        return $this->belongsTo(\App\Models\User\UserPlan::class, 'id_plan');
    }
}
