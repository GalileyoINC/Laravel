<?php

declare(strict_types=1);

namespace App\Http\Requests\EmailTemplate;

use Illuminate\Foundation\Http\FormRequest;

class EmailTemplateSendRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'to' => 'required|email',
            'subject' => 'sometimes|string|max:500',
            'body' => 'sometimes|string',
            'params' => 'sometimes|array',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function messages(): array
    {
        return [
            'to.required' => 'To email is required',
            'to.email' => 'To must be a valid email address',
            'subject.string' => 'Subject must be a string',
            'subject.max' => 'Subject cannot exceed 500 characters',
            'body.string' => 'Body must be a string',
            'params.array' => 'Params must be an array',
        ];
    }
}
