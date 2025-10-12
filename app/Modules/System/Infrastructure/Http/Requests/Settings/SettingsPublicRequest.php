<?php

declare(strict_types=1);

namespace App\Modules\System\Infrastructure\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;

class SettingsPublicRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_point_settings' => 'sometimes|array',
            'safe_settings' => 'sometimes|array',
        ];
    }
}
