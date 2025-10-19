<?php

declare(strict_types=1);

namespace App\Http\Requests\EmailPool\Web;

use Illuminate\Foundation\Http\FormRequest;

class EmailPoolIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'type' => ['nullable', 'integer'],
            'status' => ['nullable', 'integer'],
            'created_at_from' => ['nullable', 'date'],
            'created_at_to' => ['nullable', 'date'],
        ];
    }
}
