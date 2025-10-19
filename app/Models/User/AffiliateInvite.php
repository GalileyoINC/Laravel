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
 * Class AffiliateInvite
 *
 * @property int $id
 * @property int $id_user
 * @property string $email
 * @property string|null $phone_number
 * @property string|null $token
 * @property array|null $params
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property User $user
 */
class AffiliateInvite extends Model
{
    use HasFactory;

    protected $table = 'affiliate_invite';

    protected $casts = [
        'id_user' => 'int',
        'params' => 'json',
    ];

    protected $hidden = [
        'token',
    ];

    protected $fillable = [
        'id_user',
        'email',
        'phone_number',
        'token',
        'params',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
