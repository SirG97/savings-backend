<?php

namespace App\Http\Requests\Customer;

use App\Enums\Gender;
use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;

class CustomerUpdateRequest extends BaseFormRequest
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
            'id' => 'required|exists:customers,id',
            'first_name' => 'sometimes|string|max:200',
            'surname' => 'sometimes|string|max:200',
            'middle_name' => 'sometimes|string',
            'dob' => 'sometimes|date',
            'sex' => 'sometimes|in:' . implode(',', Gender::toArray()),
            'resident_state' => 'sometimes|string',
            'resident_lga' => 'sometimes|string',
            'resident_address' => 'sometimes|string',
            'occupation' => 'sometimes|string',
            'office_address' => 'sometimes|string',
            'state' => 'sometimes|string',
            'lga' => 'sometimes|string',
            'hometown' => 'sometimes|string',
            'phone' => 'sometimes|string|max:13|unique:customers,phone,' . $this->input('id'),
            'next_of_kin'  => 'sometimes|string',
            'relationship' => 'sometimes|string',
            'nok_phone' => 'sometimes|string',
            'acc_no'  => 'sometimes|string',
            'branch' => 'sometimes|string',
            'group' => 'sometimes|string',
            'sb_card_no_from' => 'sometimes|string',
            'sb_card_no_to' => 'sometimes|string',
            'sb' => 'sometimes|string',
            'initial_unit'  => 'sometimes|string',
            'bank_name' => 'sometimes|string',
            'bank_code' => 'sometimes|string',
            'account_name' => 'sometimes|string',
            'account_number' => 'sometimes|string',
            'daily_amount' => 'sometimes|string',
        ];
    }
}
