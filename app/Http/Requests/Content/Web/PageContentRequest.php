<?php

declare(strict_types=1);

namespace App\Http\Requests\Content\Web;

use App\Models\Content\PageContent;
use Illuminate\Foundation\Http\FormRequest;

class PageContentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request
     */
    public function rules(): array
    {
        return [
            'content' => ['required', 'string'],
            'status' => ['nullable', 'integer', 'in:'.PageContent::STATUS_DRAFT.','.PageContent::STATUS_PUBLISHED],
        ];
    }

    /**
     * Get custom messages for validator errors
     */
    public function messages(): array
    {
        return [
            'content.required' => 'The page content is required.',
            'status.in' => 'The selected status is invalid.',
        ];
    }

    /**
     * Get custom attributes for validator errors
     */
    public function attributes(): array
    {
        return [
            'content' => 'page content',
            'status' => 'status',
        ];
    }
}
