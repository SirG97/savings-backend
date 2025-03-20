<?php

namespace App\Http\Requests\Customer;

use App\Enums\Gender;
use App\Http\Requests\BaseFormRequest;
use App\Models\SuperAdmin;
use Illuminate\Validation\Rule;

class CustomerCreateRequest extends BaseFormRequest
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
//        dd(auth()->user(), SuperAdmin::class);
        return [
            'branch_id' => [
                Rule::requiredIf(auth()->user()?->model === SuperAdmin::class),
                'integer', // Assuming branch_id should be an integer
                'nullable',
                'exists:branches,id', // Ensures branch_id exists in the branches table
            ],
            'first_name' => 'required|string|max:200',
            'surname' => 'required|string|max:200',
            'middle_name' => 'nullable|string',
            'dob' => 'nullable|date',
            'sex' => 'nullable|in:' . implode(',', Gender::toArray()),
            'resident_state' => 'nullable|string',
            'resident_lga' => 'nullable|string',
            'resident_address' => 'nullable|string',
            'occupation' => 'nullable|string',
            'office_address' => 'nullable|string',
            'state' => 'nullable|string',
            'lga' => 'nullable|string',
            'hometown' => 'nullable|string',
            'phone' => 'required',
            'next_of_kin'  => 'nullable|string',
            'relationship' => 'nullable|string',
            'nok_phone' => 'nullable|string',
            'acc_no'  => 'nullable|string',
            'group' => 'nullable|string',
            'sb_card_no_from' => 'nullable|string',
            'sb_card_no_to' => 'nullable|string',
            'sb' => 'nullable|string',
            'initial_unit'  => 'nullable|string',
            'bank_name' => 'nullable|string',
            'bank_code' => 'nullable|string',
            'account_name' => 'nullable|string',
            'account_number' => 'nullable|string',
            'daily_amount' => 'nullable|string'
        ];
    }
}
