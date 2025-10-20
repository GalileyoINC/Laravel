<?php

declare(strict_types=1);

namespace App\Http\Requests\Settings\Web;

use Illuminate\Foundation\Http\FormRequest;

class SettingsAppRequest extends FormRequest
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
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'app__android_version_strore' => ['nullable', 'string', 'max:255'],
            'app__apple_version_strore' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * Get custom messages for validator errors
     */
    /**
     * @return array<string, mixed>
     */
    public function messages(): array
    {
        return [
            'app__android_version_strore.string' => 'The Android version store must be a string.',
            'app__android_version_strore.max' => 'The Android version store may not be greater than 255 characters.',
            'app__apple_version_strore.string' => 'The Apple version store must be a string.',
            'app__apple_version_strore.max' => 'The Apple version store may not be greater than 255 characters.',
        ];
    }

    /**
     * Get custom attributes for validator errors
     */
    /**
     * @return array<string, mixed>
     */
    public function attributes(): array
    {
        return [
            'app__android_version_strore' => 'Android version store',
            'app__apple_version_strore' => 'Apple version store',
        ];
    }

    /**
     * Initialize form values
     */
    public function initValues(): void
    {
        // Initialize form values from settings
        $this->merge([
            'app__android_version_strore' => \App\Models\System\Settings::get('app__android_version_strore', ''),
            'app__apple_version_strore' => \App\Models\System\Settings::get('app__apple_version_strore', ''),
        ]);
    }
}
