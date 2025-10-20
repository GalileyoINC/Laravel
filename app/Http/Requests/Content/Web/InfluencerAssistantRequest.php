<?php

declare(strict_types=1);

namespace App\Http\Requests\Content\Web;

use Illuminate\Foundation\Http\FormRequest;

class InfluencerAssistantRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'id_influencer' => 'required|integer|exists:users,id',
            'id_assistant' => 'required|integer|exists:users,id|different:id_influencer',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    /**
     * @return array<string, mixed>
     */
    public function messages(): array
    {
        return [
            'id_influencer.required' => 'The influencer field is required.',
            'id_influencer.integer' => 'The influencer must be an integer.',
            'id_influencer.exists' => 'The selected influencer does not exist.',
            'id_assistant.required' => 'The assistant field is required.',
            'id_assistant.integer' => 'The assistant must be an integer.',
            'id_assistant.exists' => 'The selected assistant does not exist.',
            'id_assistant.different' => 'The assistant must be different from the influencer.',
        ];
    }
}
