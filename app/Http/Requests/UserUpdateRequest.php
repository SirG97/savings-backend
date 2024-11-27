<?php

namespace App\Http\Requests;

use App\Enums\UserModelType;
use App\Enums\UserType;

class UserUpdateRequest extends BaseFormRequest
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
            'id' => 'required|exists:users,id',

            'first_name' => 'sometimes|string|max:50',
            'middle_name' => 'nullable|string|max:50',
            'last_name' => 'sometimes|string|max:50',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $this->input('id'),
            'phone' => 'sometimes|string|max:50|unique:users,phone,' . $this->input('id'),
//            'model' => 'sometimes|in:'. implode(',', UserModelType::toArray()),
        ];
    }
}
