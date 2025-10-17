<?php

declare(strict_types=1);

namespace App\Http\Requests\Content\Web;

use App\Models\Content\Page;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PageRequest extends FormRequest
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
        $pageId = $this->route('page')->id ?? null;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('page', 'name')->ignore($pageId),
            ],
            'title' => ['nullable', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('page', 'slug')->ignore($pageId),
            ],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'integer', 'in:'.Page::STATUS_ON.','.Page::STATUS_OFF],
        ];
    }

    /**
     * Get custom messages for validator errors
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The page name is required.',
            'name.max' => 'The page name may not be greater than 255 characters.',
            'name.unique' => 'A page with this name already exists.',
            'title.max' => 'The page title may not be greater than 255 characters.',
            'slug.max' => 'The page slug may not be greater than 255 characters.',
            'slug.unique' => 'A page with this slug already exists.',
            'meta_keywords.max' => 'The meta keywords may not be greater than 255 characters.',
            'meta_description.max' => 'The meta description may not be greater than 255 characters.',
            'status.in' => 'The selected status is invalid.',
        ];
    }

    /**
     * Get custom attributes for validator errors
     */
    public function attributes(): array
    {
        return [
            'name' => 'page name',
            'title' => 'page title',
            'slug' => 'page slug',
            'meta_keywords' => 'meta keywords',
            'meta_description' => 'meta description',
            'status' => 'status',
        ];
    }
}
