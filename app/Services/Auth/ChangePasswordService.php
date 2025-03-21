<?php

namespace App\Services\Auth;

use App\Http\Requests\Auth\ChangePasswordRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Ikechukwukalu\Sanctumauthstarter\Notifications\PasswordChange;
use Ikechukwukalu\Sanctumauthstarter\Models\OldPassword;

class ChangePasswordService {

    public function handlePasswordChange(ChangePasswordRequest $request) : ?array
    {
        $validated = $request->validated();

        $user = Auth::user();
        $user->password = Hash::make($validated['password']);
        $user->default_password = "0";

        if ($user->save()) {

            OldPassword::create([
                'user_id' => $user->id,
                'password' => Hash::make($validated['password'])
            ]);

            if (config('sanctumauthstarter.password.notify.change', true)) {
                $user->notify(new PasswordChange());
            }

            return ['message' =>
                trans('sanctumauthstarter::passwords.changed')];
        }

        return null;
    }
}
