<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Communication;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class SmsPoolPhoto
 *
 * @property int $id
 * @property int|null $id_sms_pool
 * @property int|null $id_sms_shedule
 * @property string|null $folder_name
 * @property string|null $web_name
 * @property string|null $url
 * @property array<array-key, mixed>|null $sizes
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $uuid
 * @property-read SmsPool|null $sms_pool
 * @property-read SmsShedule|null $sms_shedule
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPoolPhoto newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPoolPhoto newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPoolPhoto query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPoolPhoto whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPoolPhoto whereFolderName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPoolPhoto whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPoolPhoto whereIdSmsPool($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPoolPhoto whereIdSmsShedule($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPoolPhoto whereSizes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPoolPhoto whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPoolPhoto whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SmsPoolPhoto whereWebName($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\SmsPoolPhotoFactory>
 */
class SmsPoolPhoto extends Model
{
    use HasFactory;

    /** @phpstan-ignore-line */
    protected $table = 'sms_pool_photo';

    protected $casts = [
        'id_sms_pool' => 'int',
        'id_sms_shedule' => 'int',
        'sizes' => 'json',
    ];

    protected $fillable = [
        'id_sms_pool',
        'id_sms_shedule',
        'folder_name',
        'web_name',
        'url',
        'sizes',
        'uuid',
    ];

    /**
     * @return BelongsTo<SmsPool, $this>
     */
    public function sms_pool(): BelongsTo
    {
        return $this->belongsTo(SmsPool::class, 'id_sms_pool');
    }

    /**
     * @return BelongsTo<SmsShedule, $this>
     */
    public function sms_shedule(): BelongsTo
    {
        return $this->belongsTo(SmsShedule::class, 'id_sms_shedule');
    }
}
