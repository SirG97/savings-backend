<?php

namespace App\Http\Requests\LoanApplication;

use App\Enums\PerformedAction;
use App\Http\Requests\BaseFormRequest;

class LoanApplicationUpdateRequest extends BaseFormRequest
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
            'id' => 'required|exists:loan_applications,id',
            'status' => 'sometimes|string|in:' . implode(',', PerformedAction::toArray()),
            'reason' => 'required_if:status,' . PerformedAction::REJECTED->value,
        ];
    }
}
