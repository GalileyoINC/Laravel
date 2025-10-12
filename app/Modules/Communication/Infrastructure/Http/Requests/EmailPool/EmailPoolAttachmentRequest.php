<?php

declare(strict_types=1);

namespace App\Modules\Communication\Infrastructure\Http\Requests\EmailPool;

use Illuminate\Foundation\Http\FormRequest;

class EmailPoolAttachmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:email_pool_attachment,id',
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => 'Attachment ID is required',
            'id.integer' => 'Attachment ID must be an integer',
            'id.exists' => 'Attachment must exist',
        ];
    }
}
