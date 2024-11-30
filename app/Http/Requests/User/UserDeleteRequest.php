<?php

namespace App\Http\Requests\User;


use App\Http\Requests\BaseFormRequest;
use Illuminate\Support\Facades\Auth;

class UserDeleteRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
        $user =  Auth::user();

        return $user->email == 'admin@divineglobalgrowth.com';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => ['required', 'exists:users,id,deleted_at,NULL'],
        ];
    }
}
