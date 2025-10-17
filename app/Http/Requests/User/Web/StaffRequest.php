<?php

declare(strict_types=1);

namespace App\Http\Requests\User\Web;

use Illuminate\Foundation\Http\FormRequest;

class StaffRequest extends FormRequest
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
        $staffId = $this->route('staff')->id ?? null;

        return [
            'username' => [
                'required',
                'string',
                'max:255',
                'unique:staff,username,'.$staffId,
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:staff,email,'.$staffId,
            ],
            'password' => [
                $staffId ? 'nullable' : 'required',
                'string',
                'min:8',
            ],
            'role' => ['nullable', 'integer', 'in:1,10'],
            'status' => ['nullable', 'integer', 'in:0,1'],
            'is_superlogin' => ['nullable', 'boolean'],
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
            'username.unique' => 'The username has already been taken.',
            'email.required' => 'The email is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.max' => 'The email may not be greater than 255 characters.',
            'email.unique' => 'The email has already been taken.',
            'password.required' => 'The password is required.',
            'password.min' => 'The password must be at least 8 characters.',
            'role.in' => 'The selected role is invalid.',
            'status.in' => 'The selected status is invalid.',
            'is_superlogin.boolean' => 'The is superlogin field must be true or false.',
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
            'password' => 'password',
            'role' => 'role',
            'status' => 'status',
            'is_superlogin' => 'is superlogin',
        ];
    }
}
