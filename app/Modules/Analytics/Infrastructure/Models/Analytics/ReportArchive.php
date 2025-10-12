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
 * Class ReportArchive
 *
 * @property int $id
 * @property string $report_id
 * @property string $name
 * @property string $data
 * @property Carbon $created_at
 * @property string|null $sort
 */
class ReportArchive extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'report_archive';

    protected $fillable = [
        'report_id',
        'name',
        'data',
        'sort',
    ];
}
