<?php

declare(strict_types=1);

namespace App\Http\Requests\Users\Web;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        $userId = $this->route('user')->id ?? null;

        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email,'.$userId,
            ],
            'password' => [
                $userId ? 'nullable' : 'required',
                'string',
                'min:8',
                'confirmed',
            ],
            'country' => ['required', 'string', 'max:2'],
            'zip' => ['nullable', 'string', 'max:10'],
            'state' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'role' => ['nullable', 'integer', 'in:1,2'],
            'status' => ['nullable', 'integer', 'in:0,1'],
        ];
    }

    /**
     * Get custom messages for validator errors
     */
    public function messages(): array
    {
        return [
            'first_name.required' => 'The first name is required.',
            'first_name.max' => 'The first name may not be greater than 255 characters.',
            'last_name.required' => 'The last name is required.',
            'last_name.max' => 'The last name may not be greater than 255 characters.',
            'email.required' => 'The email is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.max' => 'The email may not be greater than 255 characters.',
            'email.unique' => 'The email has already been taken.',
            'password.required' => 'The password is required.',
            'password.min' => 'The password must be at least 8 characters.',
            'password.confirmed' => 'The password confirmation does not match.',
            'country.required' => 'The country is required.',
            'country.max' => 'The country may not be greater than 2 characters.',
            'zip.max' => 'The zip may not be greater than 10 characters.',
            'state.max' => 'The state may not be greater than 255 characters.',
            'city.max' => 'The city may not be greater than 255 characters.',
            'role.in' => 'The selected role is invalid.',
            'status.in' => 'The selected status is invalid.',
        ];
    }

    /**
     * Get custom attributes for validator errors
     */
    public function attributes(): array
    {
        return [
            'first_name' => 'first name',
            'last_name' => 'last name',
            'email' => 'email',
            'password' => 'password',
            'country' => 'country',
            'zip' => 'zip',
            'state' => 'state',
            'city' => 'city',
            'role' => 'role',
            'status' => 'status',
        ];
    }
}
