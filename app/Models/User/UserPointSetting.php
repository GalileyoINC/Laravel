<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\User;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserPointSetting
 *
 * @property int $id
 * @property string|null $title
 * @property int $price
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property Collection|UserPointHistory[] $user_point_histories
 */
class UserPointSetting extends Model
{
    use HasFactory;

    protected $table = 'user_point_settings';

    protected $casts = [
        'price' => 'int',
    ];

    protected $fillable = [
        'title',
        'price',
    ];

    public function user_point_histories()
    {
        return $this->hasMany(App\Models\User\UserPointHistory::class, 'id_user_point_settings');
    }
}
