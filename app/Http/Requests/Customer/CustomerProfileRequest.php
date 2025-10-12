<?php

declare(strict_types=1);

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class CustomerProfileRequest extends FormRequest
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
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'phone_profile' => ['nullable', 'string', 'max:50'],
            'country' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'zip' => ['nullable', 'string', 'max:20'],
            'timezone' => ['nullable', 'string', 'max:100'],
            'image' => ['nullable', 'string', 'max:500'],
            'is_receive_subscribe' => ['nullable', 'boolean'],
            'is_receive_list' => ['nullable', 'boolean'],
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
            'first_name.string' => 'First name must be text',
            'first_name.max' => 'First name cannot exceed 255 characters',
            'last_name.string' => 'Last name must be text',
            'last_name.max' => 'Last name cannot exceed 255 characters',
            'phone_profile.string' => 'Phone number must be text',
            'phone_profile.max' => 'Phone number cannot exceed 50 characters',
            'country.string' => 'Country must be text',
            'country.max' => 'Country cannot exceed 100 characters',
            'state.string' => 'State must be text',
            'state.max' => 'State cannot exceed 100 characters',
            'zip.string' => 'ZIP code must be text',
            'zip.max' => 'ZIP code cannot exceed 20 characters',
            'timezone.string' => 'Timezone must be text',
            'timezone.max' => 'Timezone cannot exceed 100 characters',
            'image.string' => 'Image must be text',
            'image.max' => 'Image cannot exceed 500 characters',
            'is_receive_subscribe.boolean' => 'Subscribe preference must be true or false',
            'is_receive_list.boolean' => 'List preference must be true or false',
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
            'first_name' => 'first name',
            'last_name' => 'last name',
            'phone_profile' => 'phone number',
            'country' => 'country',
            'state' => 'state',
            'zip' => 'ZIP code',
            'timezone' => 'timezone',
            'image' => 'profile image',
            'is_receive_subscribe' => 'subscribe preference',
            'is_receive_list' => 'list preference',
        ];
    }
}
