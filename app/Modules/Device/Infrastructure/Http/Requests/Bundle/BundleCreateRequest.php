<?php

declare(strict_types=1);

namespace App\Modules\Device\Infrastructure\Http\Requests\Bundle;

use Illuminate\Foundation\Http\FormRequest;

class BundleCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'sometimes|string|max:1000',
            'price' => 'required|numeric|min:0',
            'services' => 'sometimes|array',
            'services.*' => 'integer|exists:services,id',
            'is_active' => 'sometimes|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name is required',
            'name.string' => 'Name must be a string',
            'name.max' => 'Name cannot exceed 255 characters',
            'description.string' => 'Description must be a string',
            'description.max' => 'Description cannot exceed 1000 characters',
            'price.required' => 'Price is required',
            'price.numeric' => 'Price must be a number',
            'price.min' => 'Price must be at least 0',
            'services.array' => 'Services must be an array',
            'services.*.integer' => 'Each service must be an integer',
            'services.*.exists' => 'Each service must exist',
            'is_active.boolean' => 'Is active must be a boolean',
        ];
    }
}
