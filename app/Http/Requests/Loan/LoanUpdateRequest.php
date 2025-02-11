<?php

namespace App\Http\Requests\Loan;

use App\Enums\Active;
use App\Http\Requests\BaseFormRequest;

class LoanUpdateRequest extends BaseFormRequest
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
            'id' => 'required|exists:loans,id',
            'name' => 'sometimes|string|max:150',
            'description' => 'sometimes|string|max:150',
            'min_amount' => 'sometimes|numeric|min:5000',
            'max_amount' => 'sometimes|numeric|min:10000000',
            'min_duration' => 'sometimes|numeric|min:1',
            'max_duration' => 'sometimes|numeric|max:6',
            'interest_rate' => 'sometimes|numeric|min:1|max:100',
            'late_payment_interest_rate' =>'sometimes|numeric|min:1|max:100',
            'active' => 'sometimes|numeric|in:' . implode(',', Active::toArray())
        ];
    }
}
