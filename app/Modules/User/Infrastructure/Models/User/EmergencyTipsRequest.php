<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Modules\User\Infrastructure\Models\User;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EmergencyTipsRequest
 *
 * @property int $id
 * @property string|null $first_name
 * @property string|null $email
 * @property Carbon $created_at
 */
class EmergencyTipsRequest extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'emergency_tips_request';

    protected $fillable = [
        'first_name',
        'email',
    ];
}
