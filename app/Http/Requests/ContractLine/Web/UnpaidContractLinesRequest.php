<?php

declare(strict_types=1);

namespace App\Http\Requests\ContractLine\Web;

use Illuminate\Foundation\Http\FormRequest;

class UnpaidContractLinesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'exp_date' => ['nullable', 'integer', 'min:0'],
            'search' => ['nullable', 'string', 'max:255'],
            'id_service' => ['nullable', 'integer'],
            'pay_interval' => ['nullable', 'integer'],
        ];
    }
}
