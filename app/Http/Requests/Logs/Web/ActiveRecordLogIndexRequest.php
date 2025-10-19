<?php

declare(strict_types=1);

namespace App\Http\Requests\Logs\Web;

use Illuminate\Foundation\Http\FormRequest;

class ActiveRecordLogIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'action_type' => ['nullable', 'string', 'max:50'],
            'model' => ['nullable', 'string', 'max:255'],
            'id_user' => ['nullable', 'integer'],
            'id_staff' => ['nullable', 'integer'],
            'created_at_from' => ['nullable', 'date'],
            'created_at_to' => ['nullable', 'date'],
        ];
    }
}
