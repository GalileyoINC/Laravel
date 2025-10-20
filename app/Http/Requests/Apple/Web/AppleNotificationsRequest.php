<?php

declare(strict_types=1);

namespace App\Http\Requests\Apple\Web;

use Illuminate\Foundation\Http\FormRequest;

class AppleNotificationsRequest extends FormRequest
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
            'notification_type' => ['nullable', 'string', 'max:100'],
            'subtype' => ['nullable', 'string', 'max:100'],
            'created_at_from' => ['nullable', 'date'],
            'created_at_to' => ['nullable', 'date'],
        ];
    }
}
