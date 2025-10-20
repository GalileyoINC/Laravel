<?php

declare(strict_types=1);

namespace App\Http\Requests\Settings\Web;

use Illuminate\Foundation\Http\FormRequest;

class SettingsMainRequest extends FormRequest
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
            'system__show_signup' => ['nullable', 'boolean'],
            'active_record_log__on' => ['nullable', 'boolean'],
            'allowed_image_extensions' => ['nullable', 'string', 'max:255'],
            'archive_db_turn_on' => ['nullable', 'boolean'],
            'mail__sandbox_email' => ['nullable', 'email', 'max:255'],
            'mail__is_sandbox' => ['nullable', 'boolean'],
            'mail__bcc_email' => ['nullable', 'email', 'max:255'],
            'mail__tech_admin_email' => ['nullable', 'email', 'max:255'],
            'mail__is_bcc' => ['nullable', 'boolean'],
            'mail_incoming__host' => ['nullable', 'string', 'max:255'],
            'mail_incoming__login' => ['nullable', 'string', 'max:255'],
            'mail_incoming__password' => ['nullable', 'string', 'max:255'],
            'chat_socket_url' => ['nullable', 'string', 'max:255'],
            'chat_socket_local_url' => ['nullable', 'string', 'max:255'],
            'chat_port' => ['nullable', 'string', 'max:255'],
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
            'system__show_signup.boolean' => 'The show signup field must be true or false.',
            'active_record_log__on.boolean' => 'The active record log field must be true or false.',
            'allowed_image_extensions.string' => 'The allowed image extensions must be a string.',
            'allowed_image_extensions.max' => 'The allowed image extensions may not be greater than 255 characters.',
            'archive_db_turn_on.boolean' => 'The archive DB turn on field must be true or false.',
            'mail__sandbox_email.email' => 'The sandbox email must be a valid email address.',
            'mail__sandbox_email.max' => 'The sandbox email may not be greater than 255 characters.',
            'mail__is_sandbox.boolean' => 'The is sandbox field must be true or false.',
            'mail__bcc_email.email' => 'The BCC email must be a valid email address.',
            'mail__bcc_email.max' => 'The BCC email may not be greater than 255 characters.',
            'mail__tech_admin_email.email' => 'The tech admin email must be a valid email address.',
            'mail__tech_admin_email.max' => 'The tech admin email may not be greater than 255 characters.',
            'mail__is_bcc.boolean' => 'The is BCC field must be true or false.',
            'mail_incoming__host.string' => 'The incoming mail host must be a string.',
            'mail_incoming__host.max' => 'The incoming mail host may not be greater than 255 characters.',
            'mail_incoming__login.string' => 'The incoming mail login must be a string.',
            'mail_incoming__login.max' => 'The incoming mail login may not be greater than 255 characters.',
            'mail_incoming__password.string' => 'The incoming mail password must be a string.',
            'mail_incoming__password.max' => 'The incoming mail password may not be greater than 255 characters.',
            'chat_socket_url.string' => 'The chat socket URL must be a string.',
            'chat_socket_url.max' => 'The chat socket URL may not be greater than 255 characters.',
            'chat_socket_local_url.string' => 'The chat socket local URL must be a string.',
            'chat_socket_local_url.max' => 'The chat socket local URL may not be greater than 255 characters.',
            'chat_port.string' => 'The chat port must be a string.',
            'chat_port.max' => 'The chat port may not be greater than 255 characters.',
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
            'system__show_signup' => 'show signup',
            'active_record_log__on' => 'active record log',
            'allowed_image_extensions' => 'allowed image extensions',
            'archive_db_turn_on' => 'archive DB turn on',
            'mail__sandbox_email' => 'sandbox email',
            'mail__is_sandbox' => 'is sandbox',
            'mail__bcc_email' => 'BCC email',
            'mail__tech_admin_email' => 'tech admin email',
            'mail__is_bcc' => 'is BCC',
            'mail_incoming__host' => 'incoming mail host',
            'mail_incoming__login' => 'incoming mail login',
            'mail_incoming__password' => 'incoming mail password',
            'chat_socket_url' => 'chat socket URL',
            'chat_socket_local_url' => 'chat socket local URL',
            'chat_port' => 'chat port',
        ];
    }

    /**
     * Initialize form values
     */
    public function initValues(): void
    {
        // Initialize form values from settings
        $this->merge([
            'system__show_signup' => \App\Models\System\Settings::get('system__show_signup', false),
            'active_record_log__on' => \App\Models\System\Settings::get('active_record_log__on', false),
            'allowed_image_extensions' => \App\Models\System\Settings::get('allowed_image_extensions', ''),
            'archive_db_turn_on' => \App\Models\System\Settings::get('archive_db_turn_on', false),
            'mail__sandbox_email' => \App\Models\System\Settings::get('mail__sandbox_email', ''),
            'mail__is_sandbox' => \App\Models\System\Settings::get('mail__is_sandbox', false),
            'mail__bcc_email' => \App\Models\System\Settings::get('mail__bcc_email', ''),
            'mail__tech_admin_email' => \App\Models\System\Settings::get('mail__tech_admin_email', ''),
            'mail__is_bcc' => \App\Models\System\Settings::get('mail__is_bcc', false),
            'mail_incoming__host' => \App\Models\System\Settings::get('mail_incoming__host', ''),
            'mail_incoming__login' => \App\Models\System\Settings::get('mail_incoming__login', ''),
            'mail_incoming__password' => \App\Models\System\Settings::get('mail_incoming__password', ''),
            'chat_socket_url' => \App\Models\System\Settings::get('chat_socket_url', ''),
            'chat_socket_local_url' => \App\Models\System\Settings::get('chat_socket_local_url', ''),
            'chat_port' => \App\Models\System\Settings::get('chat_port', ''),
        ]);
    }
}
