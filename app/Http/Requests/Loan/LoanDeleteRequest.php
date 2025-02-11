<?php

namespace App\Http\Requests\Loan;

use App\Http\Requests\BaseFormRequest;

class LoanDeleteRequest extends BaseFormRequest
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
            'id' => ['required', 'exists:loans,id,deleted_at,NULL'],
        ];
    }
}
