<?php

declare(strict_types=1);

namespace App\Http\Requests\Users\Web;

use Illuminate\Foundation\Http\FormRequest;

class UserPlanUnpaidIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>|string>
     */
    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'id_service' => ['nullable', 'integer'],
            'pay_interval' => ['nullable', 'string', 'max:50'],
            'exp_date' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
