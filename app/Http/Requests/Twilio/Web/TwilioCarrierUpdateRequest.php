<?php

declare(strict_types=1);

namespace App\Http\Requests\Twilio\Web;

use Illuminate\Foundation\Http\FormRequest;

class TwilioCarrierUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'provider_id' => ['required', 'integer', 'exists:providers,id'],
        ];
    }
}
