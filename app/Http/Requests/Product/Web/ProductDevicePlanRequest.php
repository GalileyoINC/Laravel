<?php

declare(strict_types=1);

namespace App\Http\Requests\Product\Web;

use Illuminate\Foundation\Http\FormRequest;

class ProductDevicePlanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The plan name is required.',
            'name.max' => 'The plan name may not be greater than 255 characters.',
            'price.required' => 'The price is required.',
            'price.numeric' => 'The price must be a number.',
            'price.min' => 'The price must be at least 0.',
            'is_active.boolean' => 'The is active field must be true or false.',
        ];
    }

    /**
     * Get custom attributes for validator errors
     */
    public function attributes(): array
    {
        return [
            'name' => 'plan name',
            'description' => 'description',
            'price' => 'price',
            'is_active' => 'is active',
        ];
    }
}
