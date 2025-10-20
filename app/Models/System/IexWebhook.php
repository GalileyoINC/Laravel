<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\System;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class IexWebhook
 *
 * @property int $id
 * @property string|null $iex_id
 * @property string|null $event
 * @property string|null $set
 * @property string|null $name
 * @property array<array-key, mixed>|null $data
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IexWebhook newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IexWebhook newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IexWebhook query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IexWebhook whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IexWebhook whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IexWebhook whereEvent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IexWebhook whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IexWebhook whereIexId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IexWebhook whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IexWebhook whereSet($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IexWebhook whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\IexWebhookFactory>
 */
class IexWebhook extends Model
{
    use HasFactory;

    protected $table = 'iex_webhook';

    protected $casts = [
        'data' => 'json',
    ];

    protected $fillable = [
        'iex_id',
        'event',
        'set',
        'name',
        'data',
    ];
}
