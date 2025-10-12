<?php

declare(strict_types=1);

namespace App\Http\Requests\Device;

use Illuminate\Foundation\Http\FormRequest;

class DeviceListRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'page' => 'sometimes|integer|min:1',
            'limit' => 'sometimes|integer|min:1|max:100',
            'search' => 'sometimes|string|max:255',
            'user_id' => 'sometimes|integer|exists:users,id',
            'os' => 'sometimes|string|max:50',
        ];
    }

    public function messages(): array
    {
        return [
            'page.integer' => 'Page must be an integer',
            'page.min' => 'Page must be at least 1',
            'limit.integer' => 'Limit must be an integer',
            'limit.min' => 'Limit must be at least 1',
            'limit.max' => 'Limit cannot exceed 100',
            'search.string' => 'Search must be a string',
            'search.max' => 'Search cannot exceed 255 characters',
            'user_id.integer' => 'User ID must be an integer',
            'user_id.exists' => 'User must exist',
            'os.string' => 'OS must be a string',
            'os.max' => 'OS cannot exceed 50 characters',
        ];
    }
}
