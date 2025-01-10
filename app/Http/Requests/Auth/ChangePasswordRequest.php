<?php

namespace App\Http\Requests\Auth;

use Ikechukwukalu\Sanctumauthstarter\Rules\CurrentPassword;
use Ikechukwukalu\Sanctumauthstarter\Rules\DisallowOldPassword;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ChangePasswordRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'max:16',
                Password::min(8)
                    ->letters()
                    ->numbers(),
                'confirmed',
//                new DisallowOldPassword(
//                    config('sanctumauthstarter.password.check_all', ),
//                    config('sanctumauthstarter.password.number', 4)
//                )
            ],
        ];
    }

}
