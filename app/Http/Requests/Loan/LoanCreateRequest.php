<?php

namespace App\Http\Requests\Loan;

use App\Http\Requests\BaseFormRequest;

class LoanCreateRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:150',
            'description' => 'required|string|max:150',
            'min_amount' => 'required|numeric|min:5000',
            'max_amount' => 'required|numeric|min:10000000',
            'min_duration' => 'required|numeric|min:1',
            'max_duration' => 'required|numeric|max:6',
            'interest_rate' => 'required|numeric|min:1|max:100',
            'late_payment_interest_rate' =>'required|numeric|min:1|max:100',
        ];
    }
}
