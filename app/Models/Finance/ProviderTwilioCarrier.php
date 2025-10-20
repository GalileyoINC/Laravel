<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Finance;

use App\Models\System\TwilioCarrier;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class ProviderTwilioCarrier
 *
 * @property int $id_provider
 * @property int $id_twilio_carrier
 * @property-read Provider $provider
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProviderTwilioCarrier newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProviderTwilioCarrier newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProviderTwilioCarrier query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProviderTwilioCarrier whereIdProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProviderTwilioCarrier whereIdTwilioCarrier($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\ProviderTwilioCarrierFactory>
 */
class ProviderTwilioCarrier extends Model
{
    use HasFactory;

    /** @phpstan-ignore-line */
    public $incrementing = false;

    public $timestamps = false;

    protected $table = 'provider_twilio_carrier';

    protected $casts = [
        'id_provider' => 'int',
        'id_twilio_carrier' => 'int',
    ];

    protected $fillable = [
        'id_provider',
        'id_twilio_carrier',
    ];

    /**
     * @return BelongsTo<Provider, $this>
     */
    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class, 'id_provider');
    }

    /**
     * @return BelongsTo<TwilioCarrier, $this>
     */
    public function twilio_carrier(): BelongsTo
    {
        return $this->belongsTo(TwilioCarrier::class, 'id_twilio_carrier');
    }
}
