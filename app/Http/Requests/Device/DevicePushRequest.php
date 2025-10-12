<?php

declare(strict_types=1);

namespace App\Http\Requests\Device;

use Illuminate\Foundation\Http\FormRequest;

class DevicePushRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'body' => 'required|string|max:1000',
            'data' => 'sometimes|array',
            'sound' => 'sometimes|string|max:50',
            'badge' => 'sometimes|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Title is required',
            'title.string' => 'Title must be a string',
            'title.max' => 'Title cannot exceed 255 characters',
            'body.required' => 'Body is required',
            'body.string' => 'Body must be a string',
            'body.max' => 'Body cannot exceed 1000 characters',
            'data.array' => 'Data must be an array',
            'sound.string' => 'Sound must be a string',
            'sound.max' => 'Sound cannot exceed 50 characters',
            'badge.integer' => 'Badge must be an integer',
            'badge.min' => 'Badge must be at least 0',
        ];
    }
}
