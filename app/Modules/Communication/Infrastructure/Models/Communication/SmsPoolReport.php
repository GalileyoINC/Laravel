<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Communication;

use Carbon\Carbon;
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
 * @property Carbon $day
 */
class SmsPoolReport extends Model
{
    use HasFactory;

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
