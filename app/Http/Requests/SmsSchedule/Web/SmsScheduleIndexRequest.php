<?php

declare(strict_types=1);

namespace App\Http\Requests\SmsSchedule\Web;

use Illuminate\Foundation\Http\FormRequest;

class SmsScheduleIndexRequest extends FormRequest
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
            'purpose' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'string', 'max:255'],
            'id_subscription' => ['nullable', 'integer'],
            'followerListName' => ['nullable', 'string', 'max:255'],
            'sended_at_from' => ['nullable', 'date'],
            'sended_at_to' => ['nullable', 'date'],
            'created_at_from' => ['nullable', 'date'],
            'created_at_to' => ['nullable', 'date'],
            'updated_at_from' => ['nullable', 'date'],
            'updated_at_to' => ['nullable', 'date'],
        ];
    }
}
