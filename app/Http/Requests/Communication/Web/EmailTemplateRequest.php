<?php

declare(strict_types=1);

namespace App\Http\Requests\Communication\Web;

use Illuminate\Foundation\Http\FormRequest;

class EmailTemplateRequest extends FormRequest
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
            'fromEmail' => 'required|email|max:255',
            'fromName' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'bodyPlain' => 'nullable|string',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'fromEmail.required' => 'The from email field is required.',
            'fromEmail.email' => 'The from email must be a valid email address.',
            'fromEmail.max' => 'The from email may not be greater than 255 characters.',
            'fromName.required' => 'The from name field is required.',
            'fromName.max' => 'The from name may not be greater than 255 characters.',
            'subject.required' => 'The subject field is required.',
            'subject.max' => 'The subject may not be greater than 255 characters.',
            'body.required' => 'The body field is required.',
        ];
    }
}
