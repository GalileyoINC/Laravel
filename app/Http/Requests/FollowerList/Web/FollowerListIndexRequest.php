<?php

declare(strict_types=1);

namespace App\Http\Requests\FollowerList\Web;

use Illuminate\Foundation\Http\FormRequest;

class FollowerListIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'name' => ['nullable', 'string', 'max:255'],
            'userName' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'integer', 'in:0,1'],
            'created_at_from' => ['nullable', 'date'],
            'created_at_to' => ['nullable', 'date'],
            'updated_at_from' => ['nullable', 'date'],
            'updated_at_to' => ['nullable', 'date'],
        ];
    }
}
