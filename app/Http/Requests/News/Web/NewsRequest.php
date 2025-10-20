<?php

declare(strict_types=1);

namespace App\Http\Requests\News\Web;

use Illuminate\Foundation\Http\FormRequest;

class NewsRequest extends FormRequest
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
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $newsId = $this->route('news')->id ?? null;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:news,name,'.$newsId,
            ],
            'title' => ['nullable', 'string', 'max:255'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ];
    }

    /**
     * Get custom messages for validator errors
     */
    /**
     * @return array<string, mixed>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The name is required.',
            'name.max' => 'The name may not be greater than 255 characters.',
            'name.unique' => 'The name has already been taken.',
            'title.max' => 'The title may not be greater than 255 characters.',
            'meta_keywords.max' => 'The meta keywords may not be greater than 255 characters.',
            'meta_description.max' => 'The meta description may not be greater than 255 characters.',
            'status.boolean' => 'The status field must be true or false.',
            'image.image' => 'The file must be an image.',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif, svg.',
            'image.max' => 'The image may not be greater than 2048 kilobytes.',
        ];
    }

    /**
     * Get custom attributes for validator errors
     */
    /**
     * @return array<string, mixed>
     */
    public function attributes(): array
    {
        return [
            'name' => 'name',
            'title' => 'title',
            'meta_keywords' => 'meta keywords',
            'meta_description' => 'meta description',
            'status' => 'status',
            'image' => 'image',
        ];
    }
}
