<?php

declare(strict_types=1);

namespace App\Http\Requests\EmailPool;

use Illuminate\Foundation\Http\FormRequest;

class EmailPoolAttachmentRequest extends FormRequest
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
            'id' => 'required|integer|exists:email_pool_attachment,id',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function messages(): array
    {
        return [
            'id.required' => 'Attachment ID is required',
            'id.integer' => 'Attachment ID must be an integer',
            'id.exists' => 'Attachment must exist',
        ];
    }
}
