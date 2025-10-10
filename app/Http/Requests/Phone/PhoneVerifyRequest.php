<?php

namespace App\Http\Requests\Phone;

use Illuminate\Foundation\Http\FormRequest;

class PhoneVerifyRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'phone_number' => ['required', 'string', 'regex:/^\+[1-9]\d{1,14}$/'],
            'verification_code' => ['required', 'string', 'regex:/^[0-9]{4,6}$/'],
            'country_code' => ['nullable', 'string', 'max:10'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'phone_number.required' => 'Phone number is required',
            'phone_number.string' => 'Phone number must be text',
            'phone_number.regex' => 'Phone number must be in international format (e.g., +1234567890)',
            'verification_code.required' => 'Verification code is required',
            'verification_code.string' => 'Verification code must be text',
            'verification_code.regex' => 'Verification code must be 4-6 digits',
            'country_code.string' => 'Country code must be text',
            'country_code.max' => 'Country code cannot exceed 10 characters',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'phone_number' => 'phone number',
            'verification_code' => 'verification code',
            'country_code' => 'country code',
        ];
    }
}