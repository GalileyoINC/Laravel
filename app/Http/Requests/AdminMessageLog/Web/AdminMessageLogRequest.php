<?php

declare(strict_types=1);

namespace App\Http\Requests\AdminMessageLog\Web;

use Illuminate\Foundation\Http\FormRequest;

class AdminMessageLogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'objType' => ['nullable', 'string', 'max:100'],
            'objId' => ['nullable', 'integer'],
            'search' => ['nullable', 'string', 'max:255'],
            'created_at_from' => ['nullable', 'date'],
            'created_at_to' => ['nullable', 'date'],
        ];
    }
}
