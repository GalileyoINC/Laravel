<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\System;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class TwilioCarrier
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon $created_at
 * @property-read \App\Models\Finance\Provider|null $provider
 * @property-read Collection<int, \App\Models\Finance\Provider> $providers
 * @property-read int|null $providers_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TwilioCarrier newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TwilioCarrier newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TwilioCarrier query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TwilioCarrier whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TwilioCarrier whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TwilioCarrier whereName($value)
 *
 * @mixin \Eloquent
 */
class TwilioCarrier extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'twilio_carrier';

    protected $casts = [
        'created_at' => 'datetime',
    ];

    protected $fillable = [
        'name',
    ];

    public function providers(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Finance\Provider::class, 'provider_twilio_carrier', 'id_twilio_carrier', 'id_provider');
    }

    // Alias expected by controllers
    public function provider(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Finance\Provider::class, 'provider_id');
    }
}
