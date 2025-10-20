<?php

declare(strict_types=1);

namespace App\Http\Requests\Logs\Web;

use Illuminate\Foundation\Http\FormRequest;

class ApiLogIndexRequest extends FormRequest
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
            'key' => ['nullable', 'string', 'max:255'],
            'created_at_from' => ['nullable', 'date'],
            'created_at_to' => ['nullable', 'date'],
        ];
    }
}
