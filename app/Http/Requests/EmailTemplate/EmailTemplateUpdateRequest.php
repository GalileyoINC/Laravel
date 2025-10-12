<?php

declare(strict_types=1);

namespace App\Http\Requests\EmailTemplate;

use Illuminate\Foundation\Http\FormRequest;

class EmailTemplateUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'subject' => 'sometimes|string|max:500',
            'body' => 'sometimes|string',
            'params' => 'sometimes|array',
            'status' => 'sometimes|integer|in:0,1',
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => 'Name must be a string',
            'name.max' => 'Name cannot exceed 255 characters',
            'subject.string' => 'Subject must be a string',
            'subject.max' => 'Subject cannot exceed 500 characters',
            'body.string' => 'Body must be a string',
            'params.array' => 'Params must be an array',
            'status.integer' => 'Status must be an integer',
            'status.in' => 'Status must be 0 or 1',
        ];
    }
}
