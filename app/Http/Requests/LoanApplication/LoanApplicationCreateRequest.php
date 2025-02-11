<?php

namespace App\Http\Requests\LoanApplication;

use App\Http\Requests\BaseFormRequest;

class LoanApplicationCreateRequest extends BaseFormRequest
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
            'amount' => 'required|numeric|min:50000|max:1000000',
            'duration' => 'required|numeric|min:1|max:6',
        ];
    }
}
