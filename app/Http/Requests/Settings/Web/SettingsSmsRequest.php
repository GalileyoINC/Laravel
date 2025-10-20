<?php

declare(strict_types=1);

namespace App\Http\Requests\Settings\Web;

use Illuminate\Foundation\Http\FormRequest;

class SettingsSmsRequest extends FormRequest
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
            'sms__regular_provider' => ['nullable', 'string', 'max:255'],
            'sms__customer_provider' => ['nullable', 'string', 'max:255'],
            'sms__satellite_regular_provider' => ['nullable', 'string', 'max:255'],
            'sms__satellite_customer_provider' => ['nullable', 'string', 'max:255'],
            'sms__stop_not_valid' => ['nullable', 'boolean'],
            'sms__stop_satellite_not_valid' => ['nullable', 'boolean'],
            'sms__turn_on' => ['nullable', 'boolean'],
            'sms__influencer_phone' => ['nullable', 'boolean'],
            'sms__influencer_bivy' => ['nullable', 'boolean'],
            'sms__influencer_satellite' => ['nullable', 'boolean'],
            'sms__influencer_pivotel' => ['nullable', 'boolean'],
            'sms__twilio_sid' => ['nullable', 'string', 'max:255'],
            'sms__twilio_token' => ['nullable', 'string', 'max:255'],
            'sms__twilio_from' => ['nullable', 'string', 'max:255'],
            'sms__sinch_service_plan_id' => ['nullable', 'string', 'max:255'],
            'sms__sinch_bearer_token' => ['nullable', 'string', 'max:255'],
            'sms__sinch_from' => ['nullable', 'string', 'max:255'],
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
            'sms__regular_provider.string' => 'The regular provider must be a string.',
            'sms__regular_provider.max' => 'The regular provider may not be greater than 255 characters.',
            'sms__customer_provider.string' => 'The customer provider must be a string.',
            'sms__customer_provider.max' => 'The customer provider may not be greater than 255 characters.',
            'sms__satellite_regular_provider.string' => 'The satellite regular provider must be a string.',
            'sms__satellite_regular_provider.max' => 'The satellite regular provider may not be greater than 255 characters.',
            'sms__satellite_customer_provider.string' => 'The satellite customer provider must be a string.',
            'sms__satellite_customer_provider.max' => 'The satellite customer provider may not be greater than 255 characters.',
            'sms__stop_not_valid.boolean' => 'The stop not valid field must be true or false.',
            'sms__stop_satellite_not_valid.boolean' => 'The stop satellite not valid field must be true or false.',
            'sms__turn_on.boolean' => 'The SMS turn on field must be true or false.',
            'sms__influencer_phone.boolean' => 'The influencer phone field must be true or false.',
            'sms__influencer_bivy.boolean' => 'The influencer bivy field must be true or false.',
            'sms__influencer_satellite.boolean' => 'The influencer satellite field must be true or false.',
            'sms__influencer_pivotel.boolean' => 'The influencer pivotel field must be true or false.',
            'sms__twilio_sid.string' => 'The Twilio SID must be a string.',
            'sms__twilio_sid.max' => 'The Twilio SID may not be greater than 255 characters.',
            'sms__twilio_token.string' => 'The Twilio token must be a string.',
            'sms__twilio_token.max' => 'The Twilio token may not be greater than 255 characters.',
            'sms__twilio_from.string' => 'The Twilio from must be a string.',
            'sms__twilio_from.max' => 'The Twilio from may not be greater than 255 characters.',
            'sms__sinch_service_plan_id.string' => 'The Sinch service plan ID must be a string.',
            'sms__sinch_service_plan_id.max' => 'The Sinch service plan ID may not be greater than 255 characters.',
            'sms__sinch_bearer_token.string' => 'The Sinch bearer token must be a string.',
            'sms__sinch_bearer_token.max' => 'The Sinch bearer token may not be greater than 255 characters.',
            'sms__sinch_from.string' => 'The Sinch from must be a string.',
            'sms__sinch_from.max' => 'The Sinch from may not be greater than 255 characters.',
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
            'sms__regular_provider' => 'regular provider',
            'sms__customer_provider' => 'customer provider',
            'sms__satellite_regular_provider' => 'satellite regular provider',
            'sms__satellite_customer_provider' => 'satellite customer provider',
            'sms__stop_not_valid' => 'stop not valid',
            'sms__stop_satellite_not_valid' => 'stop satellite not valid',
            'sms__turn_on' => 'SMS turn on',
            'sms__influencer_phone' => 'influencer phone',
            'sms__influencer_bivy' => 'influencer bivy',
            'sms__influencer_satellite' => 'influencer satellite',
            'sms__influencer_pivotel' => 'influencer pivotel',
            'sms__twilio_sid' => 'Twilio SID',
            'sms__twilio_token' => 'Twilio token',
            'sms__twilio_from' => 'Twilio from',
            'sms__sinch_service_plan_id' => 'Sinch service plan ID',
            'sms__sinch_bearer_token' => 'Sinch bearer token',
            'sms__sinch_from' => 'Sinch from',
        ];
    }

    /**
     * Initialize form values
     */
    public function initValues(): void
    {
        // Initialize form values from settings
        $this->merge([
            'sms__regular_provider' => \App\Models\System\Settings::get('sms__regular_provider', ''),
            'sms__customer_provider' => \App\Models\System\Settings::get('sms__customer_provider', ''),
            'sms__satellite_regular_provider' => \App\Models\System\Settings::get('sms__satellite_regular_provider', ''),
            'sms__satellite_customer_provider' => \App\Models\System\Settings::get('sms__satellite_customer_provider', ''),
            'sms__stop_not_valid' => \App\Models\System\Settings::get('sms__stop_not_valid', false),
            'sms__stop_satellite_not_valid' => \App\Models\System\Settings::get('sms__stop_satellite_not_valid', false),
            'sms__turn_on' => \App\Models\System\Settings::get('sms__turn_on', false),
            'sms__influencer_phone' => \App\Models\System\Settings::get('sms__influencer_phone', false),
            'sms__influencer_bivy' => \App\Models\System\Settings::get('sms__influencer_bivy', false),
            'sms__influencer_satellite' => \App\Models\System\Settings::get('sms__influencer_satellite', false),
            'sms__influencer_pivotel' => \App\Models\System\Settings::get('sms__influencer_pivotel', false),
            'sms__twilio_sid' => \App\Models\System\Settings::get('sms__twilio_sid', ''),
            'sms__twilio_token' => \App\Models\System\Settings::get('sms__twilio_token', ''),
            'sms__twilio_from' => \App\Models\System\Settings::get('sms__twilio_from', ''),
            'sms__sinch_service_plan_id' => \App\Models\System\Settings::get('sms__sinch_service_plan_id', ''),
            'sms__sinch_bearer_token' => \App\Models\System\Settings::get('sms__sinch_bearer_token', ''),
            'sms__sinch_from' => \App\Models\System\Settings::get('sms__sinch_from', ''),
        ]);
    }
}
