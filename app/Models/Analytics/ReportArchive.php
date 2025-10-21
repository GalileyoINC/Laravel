<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Analytics;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ReportArchive
 *
 * @property int $id
 * @property string $report_id
 * @property string $name
 * @property string $data
 * @property string $created_at
 * @property string|null $sort
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportArchive newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportArchive newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportArchive query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportArchive whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportArchive whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportArchive whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportArchive whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportArchive whereReportId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReportArchive whereSort($value)
 *
 * @mixin \Eloquent
 *
 * @method static \Database\Factories\ReportArchiveFactory factory()
 */
/**
 * @phpstan-use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\ReportArchiveFactory>
 */
class ReportArchive extends Model
{
    /** @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\ReportArchiveFactory> */
    use HasFactory;

    /** @phpstan-ignore-line */
    public $timestamps = false;

    protected $table = 'report_archive';

    protected $fillable = [
        'report_id',
        'name',
        'data',
        'sort',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Factories\Factory<ReportArchive>
     */
    protected static function newFactory(): \Illuminate\Database\Eloquent\Factories\Factory
    {
        return \Database\Factories\ReportArchiveFactory::new();
    }
}
