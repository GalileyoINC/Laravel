<?php

declare(strict_types=1);

namespace App\Http\Requests\Device\Web;

use Illuminate\Foundation\Http\FormRequest;

class DeviceIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'page' => ['nullable', 'integer', 'min:1'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:200'],
            'search' => ['nullable', 'string', 'max:255'],
            'user_id' => ['nullable', 'integer'],
            'os' => ['nullable', 'string', 'in:ios,android,web,other'],
            'push_token_fill' => ['nullable', 'integer', 'in:0,1'],
            'push_token' => ['nullable', 'string', 'max:255'],
            'push_turn_on' => ['nullable', 'integer', 'in:0,1'],
            'updated_at_from' => ['nullable', 'date'],
            'updated_at_to' => ['nullable', 'date'],
        ];
    }
}
