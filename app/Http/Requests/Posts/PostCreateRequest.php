<?php

declare(strict_types=1);

namespace App\Http\Requests\Posts;

use Illuminate\Foundation\Http\FormRequest;

class PostCreateRequest extends FormRequest
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
            'content' => 'required|string|max:5000',
            'media.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,mov,avi|max:10240',
            'user_id' => 'nullable|integer|exists:user,id',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function messages(): array
    {
        return [
            'content.required' => 'Content is required',
            'content.max' => 'Content cannot exceed 5000 characters',
            'media.*.mimes' => 'Media files must be images or videos',
            'media.*.max' => 'Media files cannot exceed 10MB',
            'user_id.exists' => 'Selected user does not exist',
        ];
    }
}
