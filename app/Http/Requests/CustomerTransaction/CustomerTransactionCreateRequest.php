<?php

namespace App\Http\Requests\CustomerTransaction;

use App\Enums\PaymentMethod;
use App\Enums\TransactionType;
use App\Http\Requests\BaseFormRequest;
use App\Models\SuperAdmin;
use Illuminate\Validation\Rule;

class CustomerTransactionCreateRequest extends BaseFormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_id' => 'required|exists:customers,id',
            'transaction_type' => 'required|in:' . implode(',', TransactionType::toArray()),
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|in:' . implode(',', PaymentMethod::toArray()),
            'description' => 'required|string|max:50',
            'date' => 'nullable|date',
            'remark' => 'nullable|string|max:50',
        ];
    }
}
