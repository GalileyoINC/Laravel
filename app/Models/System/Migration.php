<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\System;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Migration
 *
 * @property string $version
 * @property int|null $apply_time
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Migration newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Migration newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Migration query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Migration whereApplyTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Migration whereVersion($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\MigrationFactory>
 */
class Migration extends Model
{
    use HasFactory;

    public $incrementing = false;

    public $timestamps = false;

    protected $table = 'migration';

    protected $primaryKey = 'version';

    protected $casts = [
        'apply_time' => 'int',
    ];

    protected $fillable = [
        'apply_time',
    ];
}
