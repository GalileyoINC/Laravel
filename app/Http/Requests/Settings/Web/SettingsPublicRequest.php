<?php

declare(strict_types=1);

namespace App\Http\Requests\Settings\Web;

use Illuminate\Foundation\Http\FormRequest;

class SettingsPublicRequest extends FormRequest
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
            'public__site_name' => ['nullable', 'string', 'max:255'],
            'public__site_description' => ['nullable', 'string', 'max:500'],
            'public__contact_email' => ['nullable', 'email', 'max:255'],
            'public__support_email' => ['nullable', 'email', 'max:255'],
            'public__privacy_policy' => ['nullable', 'string'],
            'public__terms_of_service' => ['nullable', 'string'],
        ];
    }

    /**
     * Get custom messages for validator errors
     */
    public function messages(): array
    {
        return [
            'public__site_name.string' => 'The site name must be a string.',
            'public__site_name.max' => 'The site name may not be greater than 255 characters.',
            'public__site_description.string' => 'The site description must be a string.',
            'public__site_description.max' => 'The site description may not be greater than 500 characters.',
            'public__contact_email.email' => 'The contact email must be a valid email address.',
            'public__contact_email.max' => 'The contact email may not be greater than 255 characters.',
            'public__support_email.email' => 'The support email must be a valid email address.',
            'public__support_email.max' => 'The support email may not be greater than 255 characters.',
            'public__privacy_policy.string' => 'The privacy policy must be a string.',
            'public__terms_of_service.string' => 'The terms of service must be a string.',
        ];
    }

    /**
     * Get custom attributes for validator errors
     */
    public function attributes(): array
    {
        return [
            'public__site_name' => 'site name',
            'public__site_description' => 'site description',
            'public__contact_email' => 'contact email',
            'public__support_email' => 'support email',
            'public__privacy_policy' => 'privacy policy',
            'public__terms_of_service' => 'terms of service',
        ];
    }

    /**
     * Initialize form values
     */
    public function initValues(): void
    {
        // Initialize form values from settings
        $this->merge([
            'public__site_name' => \App\Models\System\Settings::get('public__site_name', ''),
            'public__site_description' => \App\Models\System\Settings::get('public__site_description', ''),
            'public__contact_email' => \App\Models\System\Settings::get('public__contact_email', ''),
            'public__support_email' => \App\Models\System\Settings::get('public__support_email', ''),
            'public__privacy_policy' => \App\Models\System\Settings::get('public__privacy_policy', ''),
            'public__terms_of_service' => \App\Models\System\Settings::get('public__terms_of_service', ''),
        ]);
    }
}
