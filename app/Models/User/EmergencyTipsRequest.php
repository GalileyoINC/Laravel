<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EmergencyTipsRequest
 *
 * @property int $id
 * @property string|null $first_name
 * @property string|null $email
 * @property \Illuminate\Support\Carbon $created_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmergencyTipsRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmergencyTipsRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmergencyTipsRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmergencyTipsRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmergencyTipsRequest whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmergencyTipsRequest whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmergencyTipsRequest whereId($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\EmergencyTipsRequestFactory>
 */
class EmergencyTipsRequest extends Model
{
    use HasFactory;

    /** @phpstan-ignore-line */
    public $timestamps = false;

    protected $table = 'emergency_tips_request';

    protected $casts = [
        'created_at' => 'datetime',
    ];

    protected $fillable = [
        'first_name',
        'email',
    ];
}
