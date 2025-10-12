<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\System;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TwilioCarrier
 *
 * @property int $id
 * @property string $name
 * @property Carbon $created_at
 * @property Collection|Provider[] $providers
 */
class TwilioCarrier extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'twilio_carrier';

    protected $fillable = [
        'name',
    ];

    public function providers()
    {
        return $this->belongsToMany(Provider::class, 'provider_twilio_carrier', 'id_twilio_carrier', 'id_provider');
    }
}
