<?php

declare(strict_types=1);

namespace App\Http\Requests\Authentication;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:6', 'max:255'],
            'device_uuid' => ['nullable', 'string', 'max:255'],
            'device_os' => ['nullable', 'string', 'max:50'],
            'device_model' => ['nullable', 'string', 'max:100'],
            'push_token' => ['nullable', 'string', 'max:500'],
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
            'email.required' => 'Email is required',
            'email.email' => 'Please provide a valid email address',
            'email.max' => 'Email cannot exceed 255 characters',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 6 characters',
            'password.max' => 'Password cannot exceed 255 characters',
            'device_uuid.max' => 'Device UUID cannot exceed 255 characters',
            'device_os.max' => 'Device OS cannot exceed 50 characters',
            'device_model.max' => 'Device model cannot exceed 100 characters',
            'push_token.max' => 'Push token cannot exceed 500 characters',
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
            'email' => 'email address',
            'password' => 'password',
            'device_uuid' => 'device UUID',
            'device_os' => 'device operating system',
            'device_model' => 'device model',
            'push_token' => 'push notification token',
        ];
    }
}
