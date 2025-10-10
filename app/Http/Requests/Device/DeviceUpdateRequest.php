<?php

namespace App\Http\Requests\Device;

use Illuminate\Foundation\Http\FormRequest;

class DeviceUpdateRequest extends FormRequest
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
            'uuid' => ['required', 'string', 'max:255'],
            'os' => ['nullable', 'string', 'max:50'],
            'push_token' => ['nullable', 'string', 'max:500'],
            'params' => ['nullable', 'array'],
            'push_turn_on' => ['nullable', 'boolean'],
            'device_model' => ['nullable', 'string', 'max:100'],
            'os_version' => ['nullable', 'string', 'max:50'],
            'app_version' => ['nullable', 'string', 'max:50'],
            'screen_resolution' => ['nullable', 'string', 'max:50'],
            'timezone' => ['nullable', 'string', 'max:100'],
            'language' => ['nullable', 'string', 'max:10'],
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
            'uuid.required' => 'Device UUID is required',
            'uuid.string' => 'Device UUID must be text',
            'uuid.max' => 'Device UUID cannot exceed 255 characters',
            'os.string' => 'Operating system must be text',
            'os.max' => 'Operating system cannot exceed 50 characters',
            'push_token.string' => 'Push token must be text',
            'push_token.max' => 'Push token cannot exceed 500 characters',
            'params.array' => 'Device parameters must be an array',
            'push_turn_on.boolean' => 'Push notification status must be true or false',
            'device_model.string' => 'Device model must be text',
            'device_model.max' => 'Device model cannot exceed 100 characters',
            'os_version.string' => 'OS version must be text',
            'os_version.max' => 'OS version cannot exceed 50 characters',
            'app_version.string' => 'App version must be text',
            'app_version.max' => 'App version cannot exceed 50 characters',
            'screen_resolution.string' => 'Screen resolution must be text',
            'screen_resolution.max' => 'Screen resolution cannot exceed 50 characters',
            'timezone.string' => 'Timezone must be text',
            'timezone.max' => 'Timezone cannot exceed 100 characters',
            'language.string' => 'Language must be text',
            'language.max' => 'Language cannot exceed 10 characters',
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
            'uuid' => 'device UUID',
            'os' => 'operating system',
            'push_token' => 'push notification token',
            'params' => 'device parameters',
            'push_turn_on' => 'push notification status',
            'device_model' => 'device model',
            'os_version' => 'OS version',
            'app_version' => 'app version',
            'screen_resolution' => 'screen resolution',
            'timezone' => 'timezone',
            'language' => 'language',
        ];
    }
}