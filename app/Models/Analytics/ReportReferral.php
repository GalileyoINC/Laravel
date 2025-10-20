<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Analytics;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ReportReferral
 *
 * @property int $id
 * @property string|null $period
 * @property int $influencer_percent
 * @property array<array-key, mixed>|null $data
 * @property string $created_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportReferral newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportReferral newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportReferral query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportReferral whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportReferral whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportReferral whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportReferral whereInfluencerPercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportReferral wherePeriod($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\AnalyticsReportReferralFactory>
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
