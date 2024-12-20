<?php

namespace App\Services\Auth;

use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Ikechukwukalu\Sanctumauthstarter\Notifications\WelcomeUser;
use Ikechukwukalu\Sanctumauthstarter\Events\EmailVerification;

class RegisterService
{

    /**
     * Handle the request.
     *
     * @param  \App\Http\Requests\RegisterRequest  $request
     * @return ?array
     */
    public function handleRegistration(RegisterRequest $request): ?array
    {
        $validated = $request->validated();
        $user =  User::create([
            'email' => $validated['email'],
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'name' => $validated['first_name']. ' ' . $validated['last_name'],
            'password' => Hash::make($validated['password']),
            'form_signup' => true,
        ]);

        if (!isset($user->id)) {
            return null;
        }

        $data = [];
        if (config('sanctumauthstarter.registration.autologin', false)) {
            Auth::login($user);
            $token = $user->createToken($validated['email']);
            $data['access_token'] = $token->plainTextToken;
        }

        if (config('sanctumauthstarter.registration.notify.welcome', true)) {
            $user->notify(new WelcomeUser($user));
        }

        if (config('sanctumauthstarter.registration.notify.verify', true)) {
            EmailVerification::dispatch($user);
        }

        $data['message'] = trans('sanctumauthstarter::register.success');

        return $data;
    }
}
