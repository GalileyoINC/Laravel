<?php

declare(strict_types=1);

namespace App\Http\Requests\Finance\Web;

use Illuminate\Foundation\Http\FormRequest;

class MoneyTransactionIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'transaction_type' => ['nullable', 'string', 'max:50'],
            'is_success' => ['nullable', 'integer', 'in:0,1'],
            'is_void' => ['nullable', 'integer', 'in:0,1'],
            'is_test' => ['nullable', 'integer', 'in:0,1'],
            'createTimeRange' => ['nullable', 'string', 'max:255'],
            'total_min' => ['nullable', 'numeric'],
            'total_max' => ['nullable', 'numeric'],
        ];
    }
}
