<?php

declare(strict_types=1);

namespace App\Models\Analytics;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read \App\Models\Communication\SmsPool|null $news
 * @property-read \App\Models\User\User|null $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report query()
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\ReportFactory>
 */
class Report extends Model
{
    use HasFactory;

    /** @phpstan-ignore-line */
    protected $table = 'report';

    protected $fillable = [
        'id_news',
        'id_user',
        'reason',
        'additional_text',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * @return BelongsTo<\App\Models\User\User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User\User::class, 'id_user');
    }

    /**
     * @return BelongsTo<\App\Models\Communication\SmsPool, $this>
     */
    public function news(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Communication\SmsPool::class, 'id_news');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Factories\Factory<Report>
     */
    protected static function newFactory(): \Illuminate\Database\Eloquent\Factories\Factory
    {
        return \Database\Factories\ReportFactory::new();
    }
}
