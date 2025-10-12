<?php

declare(strict_types=1);

namespace App\Http\Requests\Bundle;

use Illuminate\Foundation\Http\FormRequest;

class BundleDeviceDataRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'idDevice' => 'required|integer|exists:services,id',
        ];
    }

    public function messages(): array
    {
        return [
            'idDevice.required' => 'Device ID is required',
            'idDevice.integer' => 'Device ID must be an integer',
            'idDevice.exists' => 'Device must exist',
        ];
    }
}
