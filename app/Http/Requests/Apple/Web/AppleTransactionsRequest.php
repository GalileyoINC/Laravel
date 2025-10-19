<?php

declare(strict_types=1);

namespace App\Http\Requests\Apple\Web;

use Illuminate\Foundation\Http\FormRequest;

class AppleTransactionsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'string', 'max:100'],
            'is_process' => ['nullable', 'boolean'],
            'id_user' => ['nullable', 'integer'],
            'created_at_from' => ['nullable', 'date'],
            'created_at_to' => ['nullable', 'date'],
        ];
    }
}
