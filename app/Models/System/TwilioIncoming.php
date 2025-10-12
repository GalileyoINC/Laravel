<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\System;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TwilioIncoming
 *
 * @property int $id
 * @property string|null $number
 * @property string|null $body
 * @property array|null $message
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property int|null $type
 */
class TwilioIncoming extends Model
{
    use HasFactory;

    protected $table = 'twilio_incoming';

    protected $casts = [
        'message' => 'json',
        'type' => 'int',
    ];

    protected $fillable = [
        'number',
        'body',
        'message',
        'type',
    ];
}
