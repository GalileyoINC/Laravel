<?php

declare(strict_types=1);

namespace App\Http\Requests\User\Web;

use Illuminate\Foundation\Http\FormRequest;

class StaffIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'integer', 'in:0,1'],
            'role' => ['nullable', 'string', 'max:50'],
            'created_at' => ['nullable', 'date'],
        ];
    }
}
