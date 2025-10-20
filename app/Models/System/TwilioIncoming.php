<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\System;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TwilioIncoming
 *
 * @property int $id
 * @property string|null $number
 * @property string|null $body
 * @property array<array-key, mixed>|null $message
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $type
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TwilioIncoming newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TwilioIncoming newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TwilioIncoming query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TwilioIncoming whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TwilioIncoming whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TwilioIncoming whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TwilioIncoming whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TwilioIncoming whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TwilioIncoming whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TwilioIncoming whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\TwilioIncomingFactory>
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
