<?php

declare(strict_types=1);

namespace App\Http\Requests\SmsPool\Web;

use Illuminate\Foundation\Http\FormRequest;

class SmsPoolArchiveIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'purpose' => ['nullable', 'string', 'max:255'],
            'id_subscription' => ['nullable', 'integer'],
            'followerListName' => ['nullable', 'string', 'max:255'],
            'created_at_from' => ['nullable', 'date'],
            'created_at_to' => ['nullable', 'date'],
            'updated_at_from' => ['nullable', 'date'],
            'updated_at_to' => ['nullable', 'date'],
        ];
    }
}
