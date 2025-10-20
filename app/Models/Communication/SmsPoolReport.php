<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Communication;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SmsPoolReport
 *
 * @property int $id
 * @property int|null $influencer_min
 * @property int|null $influencer_max
 * @property float|null $influencer_avg
 * @property float|null $influencer_median
 * @property int|null $influencer_total
 * @property int|null $influencer_users
 * @property int|null $api_min
 * @property int|null $api_max
 * @property float|null $api_avg
 * @property float|null $api_median
 * @property int|null $api_total
 * @property int|null $api_users
 * @property \Illuminate\Support\Carbon $day
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPoolReport newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPoolReport newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPoolReport query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPoolReport whereApiAvg($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPoolReport whereApiMax($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPoolReport whereApiMedian($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPoolReport whereApiMin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPoolReport whereApiTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPoolReport whereApiUsers($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPoolReport whereDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPoolReport whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPoolReport whereInfluencerAvg($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPoolReport whereInfluencerMax($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPoolReport whereInfluencerMedian($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPoolReport whereInfluencerMin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPoolReport whereInfluencerTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPoolReport whereInfluencerUsers($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\SmsPoolReportFactory>
 */
class SmsPoolReport extends Model
{
    use HasFactory;

    /** @phpstan-ignore-line */
    public $timestamps = false;

    protected $table = 'sms_pool_report';

    protected $casts = [
        'influencer_min' => 'int',
        'influencer_max' => 'int',
        'influencer_avg' => 'float',
        'influencer_median' => 'float',
        'influencer_total' => 'int',
        'influencer_users' => 'int',
        'api_min' => 'int',
        'api_max' => 'int',
        'api_avg' => 'float',
        'api_median' => 'float',
        'api_total' => 'int',
        'api_users' => 'int',
        'day' => 'datetime',
    ];

    protected $fillable = [
        'influencer_min',
        'influencer_max',
        'influencer_avg',
        'influencer_median',
        'influencer_total',
        'influencer_users',
        'api_min',
        'api_max',
        'api_avg',
        'api_median',
        'api_total',
        'api_users',
        'day',
    ];
}
