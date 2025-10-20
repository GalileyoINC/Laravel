<?php

declare(strict_types=1);

namespace App\Http\Requests\Twilio\Web;

use Illuminate\Foundation\Http\FormRequest;

class TwilioCarrierIndexRequest extends FormRequest
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
            'provider_id' => ['nullable', 'integer'],
            'created_at_from' => ['nullable', 'date'],
            'created_at_to' => ['nullable', 'date'],
        ];
    }
}
