<?php

namespace App\Http\Requests\Transaction;

use App\Http\Requests\BaseFormRequest;

class TransactionUpdateRequest extends BaseFormRequest
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
            'id' => 'required|exists:transactions,id',
            'name' => 'required|string|max:150'
        ];
    }
}
