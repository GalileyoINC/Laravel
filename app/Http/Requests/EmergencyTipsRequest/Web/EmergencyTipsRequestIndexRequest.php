<?php

declare(strict_types=1);

namespace App\Http\Requests\EmergencyTipsRequest\Web;

use Illuminate\Foundation\Http\FormRequest;

class EmergencyTipsRequestIndexRequest extends FormRequest
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
            'search' => ['nullable', 'string', 'max:255'],
            'first_name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'max:255'],
            'created_at_from' => ['nullable', 'date'],
            'created_at_to' => ['nullable', 'date'],
        ];
    }
}
