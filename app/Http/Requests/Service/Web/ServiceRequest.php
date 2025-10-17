<?php

declare(strict_types=1);

namespace App\Http\Requests\Service\Web;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'bonus_point' => 'nullable|integer|min:0',
            'type' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
            'settings_max_phone_cnt' => 'nullable|integer|min:0',
            'settings_alert' => 'nullable|integer|min:0',
            'settings_notification' => 'nullable|integer|min:0',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The name field is required.',
            'name.max' => 'The name may not be greater than 255 characters.',
            'price.required' => 'The price field is required.',
            'price.numeric' => 'The price must be a number.',
            'price.min' => 'The price must be at least 0.',
            'bonus_point.integer' => 'The bonus point must be an integer.',
            'bonus_point.min' => 'The bonus point must be at least 0.',
            'type.integer' => 'The type must be an integer.',
            'is_active.boolean' => 'The is active field must be true or false.',
            'settings_max_phone_cnt.integer' => 'The max phone count must be an integer.',
            'settings_max_phone_cnt.min' => 'The max phone count must be at least 0.',
            'settings_alert.integer' => 'The alert count must be an integer.',
            'settings_alert.min' => 'The alert count must be at least 0.',
            'settings_notification.integer' => 'The notification count must be an integer.',
            'settings_notification.min' => 'The notification count must be at least 0.',
        ];
    }
}
