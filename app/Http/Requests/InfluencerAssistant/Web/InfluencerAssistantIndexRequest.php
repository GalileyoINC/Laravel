<?php

declare(strict_types=1);

namespace App\Http\Requests\InfluencerAssistant\Web;

use Illuminate\Foundation\Http\FormRequest;

class InfluencerAssistantIndexRequest extends FormRequest
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
            'userInfluencerName' => ['nullable', 'string', 'max:255'],
            'userAssistantName' => ['nullable', 'string', 'max:255'],
        ];
    }
}
