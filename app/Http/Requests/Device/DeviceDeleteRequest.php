<?php

declare(strict_types=1);

namespace App\Http\Requests\Device;

use Illuminate\Foundation\Http\FormRequest;

class DeviceDeleteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:devices,id',
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => 'Device ID is required',
            'id.integer' => 'Device ID must be an integer',
            'id.exists' => 'Device must exist',
        ];
    }
}
