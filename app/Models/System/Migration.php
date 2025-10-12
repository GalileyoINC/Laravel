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
