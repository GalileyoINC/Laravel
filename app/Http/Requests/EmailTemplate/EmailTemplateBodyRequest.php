<?php

declare(strict_types=1);

namespace App\Http\Requests\EmailTemplate;

use Illuminate\Foundation\Http\FormRequest;

class EmailTemplateBodyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:email_templates,id',
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => 'Template ID is required',
            'id.integer' => 'Template ID must be an integer',
            'id.exists' => 'Template must exist',
        ];
    }
}
