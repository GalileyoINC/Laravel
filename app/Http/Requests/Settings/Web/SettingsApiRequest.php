<?php

declare(strict_types=1);

namespace App\Http\Requests\Settings\Web;

use Illuminate\Foundation\Http\FormRequest;

class SettingsApiRequest extends FormRequest
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
            'iex_cloud__secret' => ['nullable', 'string', 'max:255'],
            'iex_cloud__public' => ['nullable', 'string', 'max:255'],
            'alphavantage__api_key' => ['nullable', 'string', 'max:255'],
            'marketstack__api_key' => ['nullable', 'string', 'max:255'],
            'authorize__name' => ['nullable', 'string', 'max:255'],
            'authorize__validation_mode' => ['nullable', 'string', 'max:255'],
            'authorize__transaction_key' => ['nullable', 'string', 'max:255'],
            'authorize__is_sandbox' => ['nullable', 'boolean'],
            'bivy__email' => ['nullable', 'email', 'max:255'],
            'bivy__password' => ['nullable', 'string', 'max:255'],
            'bivy__api_key' => ['nullable', 'string', 'max:255'],
            'pivotel__client_id' => ['nullable', 'string', 'max:255'],
            'pivotel__client_secret' => ['nullable', 'string', 'max:255'],
            'pivotel__sender_phone' => ['nullable', 'string', 'max:255'],
            'newsapi__api_key' => ['nullable', 'string', 'max:255'],
            'apple__key_id' => ['nullable', 'string', 'max:255'],
            'apple__issuer_id' => ['nullable', 'string', 'max:255'],
            'apple__bundle_id' => ['nullable', 'string', 'max:255'],
            'openai_bearer_key' => ['nullable', 'string', 'max:255'],
            'openai_chat_model' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * Get custom messages for validator errors
     */
    public function messages(): array
    {
        return [
            'iex_cloud__secret.string' => 'The IEX Cloud secret must be a string.',
            'iex_cloud__secret.max' => 'The IEX Cloud secret may not be greater than 255 characters.',
            'iex_cloud__public.string' => 'The IEX Cloud public key must be a string.',
            'iex_cloud__public.max' => 'The IEX Cloud public key may not be greater than 255 characters.',
            'alphavantage__api_key.string' => 'The AlphaVantage API key must be a string.',
            'alphavantage__api_key.max' => 'The AlphaVantage API key may not be greater than 255 characters.',
            'marketstack__api_key.string' => 'The MarketStack API key must be a string.',
            'marketstack__api_key.max' => 'The MarketStack API key may not be greater than 255 characters.',
            'authorize__name.string' => 'The Authorize name must be a string.',
            'authorize__name.max' => 'The Authorize name may not be greater than 255 characters.',
            'authorize__validation_mode.string' => 'The Authorize validation mode must be a string.',
            'authorize__validation_mode.max' => 'The Authorize validation mode may not be greater than 255 characters.',
            'authorize__transaction_key.string' => 'The Authorize transaction key must be a string.',
            'authorize__transaction_key.max' => 'The Authorize transaction key may not be greater than 255 characters.',
            'authorize__is_sandbox.boolean' => 'The Authorize is sandbox field must be true or false.',
            'bivy__email.email' => 'The Bivy email must be a valid email address.',
            'bivy__email.max' => 'The Bivy email may not be greater than 255 characters.',
            'bivy__password.string' => 'The Bivy password must be a string.',
            'bivy__password.max' => 'The Bivy password may not be greater than 255 characters.',
            'bivy__api_key.string' => 'The Bivy API key must be a string.',
            'bivy__api_key.max' => 'The Bivy API key may not be greater than 255 characters.',
            'pivotel__client_id.string' => 'The Pivotel client ID must be a string.',
            'pivotel__client_id.max' => 'The Pivotel client ID may not be greater than 255 characters.',
            'pivotel__client_secret.string' => 'The Pivotel client secret must be a string.',
            'pivotel__client_secret.max' => 'The Pivotel client secret may not be greater than 255 characters.',
            'pivotel__sender_phone.string' => 'The Pivotel sender phone must be a string.',
            'pivotel__sender_phone.max' => 'The Pivotel sender phone may not be greater than 255 characters.',
            'newsapi__api_key.string' => 'The News API key must be a string.',
            'newsapi__api_key.max' => 'The News API key may not be greater than 255 characters.',
            'apple__key_id.string' => 'The Apple key ID must be a string.',
            'apple__key_id.max' => 'The Apple key ID may not be greater than 255 characters.',
            'apple__issuer_id.string' => 'The Apple issuer ID must be a string.',
            'apple__issuer_id.max' => 'The Apple issuer ID may not be greater than 255 characters.',
            'apple__bundle_id.string' => 'The Apple bundle ID must be a string.',
            'apple__bundle_id.max' => 'The Apple bundle ID may not be greater than 255 characters.',
            'openai_bearer_key.string' => 'The OpenAI bearer key must be a string.',
            'openai_bearer_key.max' => 'The OpenAI bearer key may not be greater than 255 characters.',
            'openai_chat_model.string' => 'The OpenAI chat model must be a string.',
            'openai_chat_model.max' => 'The OpenAI chat model may not be greater than 255 characters.',
        ];
    }

    /**
     * Get custom attributes for validator errors
     */
    public function attributes(): array
    {
        return [
            'iex_cloud__secret' => 'IEX Cloud secret',
            'iex_cloud__public' => 'IEX Cloud public key',
            'alphavantage__api_key' => 'AlphaVantage API key',
            'marketstack__api_key' => 'MarketStack API key',
            'authorize__name' => 'Authorize name',
            'authorize__validation_mode' => 'Authorize validation mode',
            'authorize__transaction_key' => 'Authorize transaction key',
            'authorize__is_sandbox' => 'Authorize is sandbox',
            'bivy__email' => 'Bivy email',
            'bivy__password' => 'Bivy password',
            'bivy__api_key' => 'Bivy API key',
            'pivotel__client_id' => 'Pivotel client ID',
            'pivotel__client_secret' => 'Pivotel client secret',
            'pivotel__sender_phone' => 'Pivotel sender phone',
            'newsapi__api_key' => 'News API key',
            'apple__key_id' => 'Apple key ID',
            'apple__issuer_id' => 'Apple issuer ID',
            'apple__bundle_id' => 'Apple bundle ID',
            'openai_bearer_key' => 'OpenAI bearer key',
            'openai_chat_model' => 'OpenAI chat model',
        ];
    }

    /**
     * Initialize form values
     */
    public function initValues(): void
    {
        // Initialize form values from settings
        $this->merge([
            'iex_cloud__secret' => \App\Models\System\Settings::get('iex_cloud__secret', ''),
            'iex_cloud__public' => \App\Models\System\Settings::get('iex_cloud__public', ''),
            'alphavantage__api_key' => \App\Models\System\Settings::get('alphavantage__api_key', ''),
            'marketstack__api_key' => \App\Models\System\Settings::get('marketstack__api_key', ''),
            'authorize__name' => \App\Models\System\Settings::get('authorize__name', ''),
            'authorize__validation_mode' => \App\Models\System\Settings::get('authorize__validation_mode', ''),
            'authorize__transaction_key' => \App\Models\System\Settings::get('authorize__transaction_key', ''),
            'authorize__is_sandbox' => \App\Models\System\Settings::get('authorize__is_sandbox', false),
            'bivy__email' => \App\Models\System\Settings::get('bivy__email', ''),
            'bivy__password' => \App\Models\System\Settings::get('bivy__password', ''),
            'bivy__api_key' => \App\Models\System\Settings::get('bivy__api_key', ''),
            'pivotel__client_id' => \App\Models\System\Settings::get('pivotel__client_id', ''),
            'pivotel__client_secret' => \App\Models\System\Settings::get('pivotel__client_secret', ''),
            'pivotel__sender_phone' => \App\Models\System\Settings::get('pivotel__sender_phone', ''),
            'newsapi__api_key' => \App\Models\System\Settings::get('newsapi__api_key', ''),
            'apple__key_id' => \App\Models\System\Settings::get('apple__key_id', ''),
            'apple__issuer_id' => \App\Models\System\Settings::get('apple__issuer_id', ''),
            'apple__bundle_id' => \App\Models\System\Settings::get('apple__bundle_id', ''),
            'openai_bearer_key' => \App\Models\System\Settings::get('openai_bearer_key', ''),
            'openai_chat_model' => \App\Models\System\Settings::get('openai_chat_model', ''),
        ]);
    }
}
