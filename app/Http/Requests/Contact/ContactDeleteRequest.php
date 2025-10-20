<?php

declare(strict_types=1);

namespace App\Http\Requests\Contact;

use Illuminate\Foundation\Http\FormRequest;

class ContactDeleteRequest extends FormRequest
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
            'id' => 'required|integer|exists:contacts,id',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function messages(): array
    {
        return [
            'id.required' => 'Contact ID is required',
            'id.integer' => 'Contact ID must be an integer',
            'id.exists' => 'Contact must exist',
        ];
    }
}
