<?php

declare(strict_types=1);

namespace App\Http\Requests\Authentication\Web;

use Illuminate\Foundation\Http\FormRequest;

class SelfRequest extends FormRequest
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
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * Get custom messages for validator errors
     */
    public function messages(): array
    {
        return [
            'username.required' => 'The username is required.',
            'username.max' => 'The username may not be greater than 255 characters.',
            'email.required' => 'The email is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.max' => 'The email may not be greater than 255 characters.',
            'first_name.max' => 'The first name may not be greater than 255 characters.',
            'last_name.max' => 'The last name may not be greater than 255 characters.',
        ];
    }

    /**
     * Get custom attributes for validator errors
     */
    public function attributes(): array
    {
        return [
            'username' => 'username',
            'email' => 'email',
            'first_name' => 'first name',
            'last_name' => 'last name',
        ];
    }
}
