<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProviderTwilioCarrier
 *
 * @property int $id_provider
 * @property int $id_twilio_carrier
 * @property Provider $provider
 * @property TwilioCarrier $twilio_carrier
 */
class ProviderTwilioCarrier extends Model
{
    use HasFactory;

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

    public function provider()
    {
        return $this->belongsTo(Provider::class, 'id_provider');
    }

    public function twilio_carrier()
    {
        return $this->belongsTo(TwilioCarrier::class, 'id_twilio_carrier');
    }
}
