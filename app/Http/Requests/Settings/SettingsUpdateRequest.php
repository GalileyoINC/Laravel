<?php

declare(strict_types=1);

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;

class SettingsUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sms_settings' => 'sometimes|array',
            'main_settings' => 'sometimes|array',
            'api_settings' => 'sometimes|array',
            'app_settings' => 'sometimes|array',
        ];
    }
}

// SettingsPublicRequest.php
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
