<?php

declare(strict_types=1);

/**
 * Created by Reliese Model.
 */

namespace App\Models\Communication;

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
 * @property array<array-key, mixed>|null $params
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate whereBodyPlain($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate whereFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate whereParams($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EmailTemplate whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
/**
 * @use \Illuminate\Database\Eloquent\Factories\HasFactory<\Database\Factories\CommunicationEmailTemplateFactory>
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

    /**
     * Place variables in the email body
     */
    public function placeBody(array $variables = []): string
    {
        $body = $this->body;

        foreach ($variables as $key => $value) {
            $body = str_replace('{'.$key.'}', $value, $body);
        }

        return $body;
    }
}
