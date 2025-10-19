<?php

declare(strict_types=1);

namespace App\Http\Requests\Twilio\Web;

use Illuminate\Foundation\Http\FormRequest;

class TwilioIncomingIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'number' => ['nullable', 'string', 'max:20'],
            'created_at_from' => ['nullable', 'date'],
            'created_at_to' => ['nullable', 'date'],
        ];
    }
}
