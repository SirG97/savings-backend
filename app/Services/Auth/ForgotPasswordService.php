<?php

namespace App\Services\Auth;

use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Models\User;
use Ikechukwukalu\Sanctumauthstarter\Events\ForgotPassword;

class ForgotPasswordService
{

    /**
     * Handle the request.
     *
     * @param  \App\Http\Requests\ForgotPasswordRequest  $request
     * @return ?array
     */
    public function handleForgotPassword(ForgotPasswordRequest $request): array
    {
        $validated = $request->validated();
        $user = User::where('email', $validated['email'])
                    ->where('socialite_signup', false)->first();
        if (isset($user->email)) {
            ForgotPassword::dispatch($user);
        }

        return ['message' => trans('sanctumauthstarter::passwords.sent')];
    }
}
