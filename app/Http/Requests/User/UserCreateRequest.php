<?php

namespace App\Http\Requests\User;

use App\Enums\UserModelType;
use App\Enums\UserType;
use App\Http\Requests\BaseFormRequest;
use App\Models\SuperAdmin;
use Illuminate\Validation\Rule;

class UserCreateRequest extends BaseFormRequest
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
            'branch_id' => [
                Rule::requiredIf(auth()->user()->model === SuperAdmin::class),
                'integer', // Assuming branch_id should be an integer
                'exists:branches,id', // Ensures branch_id exists in the branches table
            ],
            'first_name' => ['required', 'string', 'max:50'],
            'last_name' => ['required', 'string', 'max:50'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:50', 'unique:users'],
            'model' => 'required|in:'. implode(',', UserModelType::toArray()),
        ];
    }
}
