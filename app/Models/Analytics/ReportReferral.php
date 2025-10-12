<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Analytics;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ReportReferral
 *
 * @property int $id
 * @property string|null $period
 * @property int $influencer_percent
 * @property array|null $data
 * @property Carbon $created_at
 */
class ReportReferral extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'report_referral';

    protected $casts = [
        'influencer_percent' => 'int',
        'data' => 'json',
    ];

    protected $fillable = [
        'period',
        'influencer_percent',
        'data',
    ];
}
