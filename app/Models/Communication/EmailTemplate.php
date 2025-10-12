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
 * Class EmailTemplate
 *
 * @property int $id
 * @property string $name
 * @property string|null $from
 * @property string $subject
 * @property string $body
 * @property string|null $bodyPlain
 * @property array|null $params
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 */
class EmailTemplate extends Model
{
    use HasFactory;

    protected $table = 'email_template';

    protected $casts = [
        'params' => 'json',
    ];

    protected $fillable = [
        'name',
        'from',
        'subject',
        'body',
        'bodyPlain',
        'params',
    ];
}
