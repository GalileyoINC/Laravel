<?php

declare(strict_types=1);

namespace App\Http\Requests\EmailPool;

use Illuminate\Foundation\Http\FormRequest;

class EmailPoolResendRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:email_pool,id',
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => 'Email ID is required',
            'id.integer' => 'Email ID must be an integer',
            'id.exists' => 'Email must exist',
        ];
    }
}
