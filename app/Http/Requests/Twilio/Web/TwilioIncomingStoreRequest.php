<?php

declare(strict_types=1);

namespace App\Http\Requests\Twilio\Web;

use Illuminate\Foundation\Http\FormRequest;

class TwilioIncomingStoreRequest extends FormRequest
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
            'number' => ['required', 'string', 'max:20'],
            'body' => ['required', 'string', 'max:1600'],
            'message' => ['nullable', 'string'],
        ];
    }
}
