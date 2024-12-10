<?php

namespace App\Http\Requests\User;


use App\Enums\Active;
use App\Http\Requests\BaseFormRequest;

class UserSuspendRequest extends BaseFormRequest
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
            'active' => 'required|numeric|in:' . implode(',', Active::toArray())
        ];
    }
}
