<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class EditProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $user_id = isset(Auth::user()->id) ? Auth::user()->id : null;
        return [
            'name' => ['required', 'string','max:100'],
            'email' => ['required', 'email', 'max:100', 'unique:users,email,' . $user_id ],
        ];
    }
}
