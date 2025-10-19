<?php

declare(strict_types=1);

namespace App\Http\Requests\Users\Web;

use Illuminate\Foundation\Http\FormRequest;

class UserPointSettingsRequest extends FormRequest
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
     *
     * @return array<string, array<int, string>|string>
     */
    public function rules(): array
    {
        return [
            'user_point__enabled' => ['nullable', 'boolean'],
            'user_point__points_per_dollar' => ['nullable', 'numeric', 'min:0'],
            'user_point__points_per_referral' => ['nullable', 'integer', 'min:0'],
            'user_point__points_per_login' => ['nullable', 'integer', 'min:0'],
            'user_point__points_per_share' => ['nullable', 'integer', 'min:0'],
            'user_point__max_points_per_day' => ['nullable', 'integer', 'min:0'],
            'user_point__redemption_rate' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    /**
     * Get custom messages for validator errors
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'user_point__enabled.boolean' => 'The user point enabled field must be true or false.',
            'user_point__points_per_dollar.numeric' => 'The points per dollar must be a number.',
            'user_point__points_per_dollar.min' => 'The points per dollar must be at least 0.',
            'user_point__points_per_referral.integer' => 'The points per referral must be an integer.',
            'user_point__points_per_referral.min' => 'The points per referral must be at least 0.',
            'user_point__points_per_login.integer' => 'The points per login must be an integer.',
            'user_point__points_per_login.min' => 'The points per login must be at least 0.',
            'user_point__points_per_share.integer' => 'The points per share must be an integer.',
            'user_point__points_per_share.min' => 'The points per share must be at least 0.',
            'user_point__max_points_per_day.integer' => 'The max points per day must be an integer.',
            'user_point__max_points_per_day.min' => 'The max points per day must be at least 0.',
            'user_point__redemption_rate.numeric' => 'The redemption rate must be a number.',
            'user_point__redemption_rate.min' => 'The redemption rate must be at least 0.',
        ];
    }

    /**
     * Get custom attributes for validator errors
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'user_point__enabled' => 'user point enabled',
            'user_point__points_per_dollar' => 'points per dollar',
            'user_point__points_per_referral' => 'points per referral',
            'user_point__points_per_login' => 'points per login',
            'user_point__points_per_share' => 'points per share',
            'user_point__max_points_per_day' => 'max points per day',
            'user_point__redemption_rate' => 'redemption rate',
        ];
    }

    /**
     * Initialize form values
     */
    public function initValues(): void
    {
        // Initialize form values from settings
        $this->merge([
            'user_point__enabled' => \App\Models\System\Settings::get('user_point__enabled', false),
            'user_point__points_per_dollar' => \App\Models\System\Settings::get('user_point__points_per_dollar', 0),
            'user_point__points_per_referral' => \App\Models\System\Settings::get('user_point__points_per_referral', 0),
            'user_point__points_per_login' => \App\Models\System\Settings::get('user_point__points_per_login', 0),
            'user_point__points_per_share' => \App\Models\System\Settings::get('user_point__points_per_share', 0),
            'user_point__max_points_per_day' => \App\Models\System\Settings::get('user_point__max_points_per_day', 0),
            'user_point__redemption_rate' => \App\Models\System\Settings::get('user_point__redemption_rate', 0),
        ]);
    }
}
