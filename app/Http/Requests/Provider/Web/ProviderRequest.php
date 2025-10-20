<?php

declare(strict_types=1);

namespace App\Http\Requests\Provider\Web;

use Illuminate\Foundation\Http\FormRequest;

class ProviderRequest extends FormRequest
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
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'country' => 'nullable|string|max:2',
            'is_satellite' => 'nullable|boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    /**
     * @return array<string, mixed>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The name field is required.',
            'name.max' => 'The name may not be greater than 255 characters.',
            'email.email' => 'The email must be a valid email address.',
            'email.max' => 'The email may not be greater than 255 characters.',
            'country.max' => 'The country may not be greater than 2 characters.',
            'is_satellite.boolean' => 'The is satellite field must be true or false.',
        ];
    }
}
