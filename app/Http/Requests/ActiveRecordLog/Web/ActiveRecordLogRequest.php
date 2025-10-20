<?php

declare(strict_types=1);

namespace App\Http\Requests\ActiveRecordLog\Web;

use Illuminate\Foundation\Http\FormRequest;

class ActiveRecordLogRequest extends FormRequest
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
            'userName' => ['nullable', 'string', 'max:255'],
            'staffName' => ['nullable', 'string', 'max:255'],
            'action_type' => ['nullable', 'integer'],
            'model' => ['nullable', 'string', 'max:255'],
            'field' => ['nullable', 'string', 'max:255'],
            'created_at_from' => ['nullable', 'date'],
            'created_at_to' => ['nullable', 'date'],
        ];
    }
}
