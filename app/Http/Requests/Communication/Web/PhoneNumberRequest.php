<?php

declare(strict_types=1);

namespace App\Http\Requests\Communication\Web;

use Illuminate\Foundation\Http\FormRequest;

class PhoneNumberRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'type' => 'required|integer',
            'number' => 'required|string|max:20',
            'is_valid' => 'nullable|boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'type.required' => 'The type field is required.',
            'type.integer' => 'The type must be an integer.',
            'number.required' => 'The number field is required.',
            'number.max' => 'The number may not be greater than 20 characters.',
            'is_valid.boolean' => 'The is valid field must be true or false.',
        ];
    }
}
