<?php

declare(strict_types=1);

namespace App\Http\Requests\Follower\Web;

use Illuminate\Foundation\Http\FormRequest;

class FollowerIndexRequest extends FormRequest
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
            'followerListName' => ['nullable', 'string', 'max:255'],
            'userLeaderName' => ['nullable', 'string', 'max:255'],
            'userFollowerName' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'integer', 'in:0,1'],
            'created_at_from' => ['nullable', 'date'],
            'created_at_to' => ['nullable', 'date'],
            'updated_at_from' => ['nullable', 'date'],
            'updated_at_to' => ['nullable', 'date'],
        ];
    }
}
