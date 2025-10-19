<?php

declare(strict_types=1);

namespace App\Http\Requests\Communication\Web;

use Illuminate\Foundation\Http\FormRequest;

class PhoneNumberIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'userName' => ['nullable', 'string', 'max:255'],
            'number' => ['nullable', 'string', 'max:50'],
            'is_valid' => ['nullable', 'integer', 'in:0,1'],
            'type' => ['nullable', 'string', 'max:50'],
            'id_provider' => ['nullable', 'integer'],
            'twilio_type' => ['nullable', 'string', 'max:50'],
            'is_active' => ['nullable', 'integer', 'in:0,1'],
            'is_primary' => ['nullable', 'integer', 'in:0,1'],
            'is_send' => ['nullable', 'integer', 'in:0,1'],
            'created_at_from' => ['nullable', 'date'],
            'created_at_to' => ['nullable', 'date'],
            'updated_at_from' => ['nullable', 'date'],
            'updated_at_to' => ['nullable', 'date'],
        ];
    }
}
