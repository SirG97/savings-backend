<?php

namespace App\Services\Auth;

use App\Actions\ResponseData;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Ikechukwukalu\Sanctumauthstarter\Events\TwoFactorLogin as TwoFactorLoginEvent;
use Ikechukwukalu\Sanctumauthstarter\Models\TwoFactorLogin;
use App\Traits\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Laragear\TwoFactor\Facades\Auth2FA;

class LoginService {

    use Helpers;

    public function handleLogin(LoginRequest $request): ?array
    {
        $validated = $request->validated();
        $remember = isset($validated['remember_me']) ? true : false;

        if (!Auth::attempt([
                'email' => $validated['email'],
                'password' => $validated['password']
            ], $remember))
        {
            return null;
        }

        $this->throttleRequestsService->clearAttempts($request);

        $user = Auth::user();

        if ($user->two_factor) {
            return $this->generateTwoFactorURL($request, $validated, $user);
        }

        return $this->finaliseLoginProcess($user, $validated);
    }

    public function handleTwoFactorLogin(Request $request): null|ResponseData
    {
        $tfa_pass = $this->getTwoFactorLoginPassword($request->uuid);

        if (!isset($tfa_pass->id)) {
            return null;
        }

        $email = $request->email;
        $salt = Crypt::decryptString($tfa_pass->salt);
        $password = str_replace($salt, "", Crypt::decryptString(
                        $tfa_pass->password));
        $validated = [
            'email' => $email,
            'password' => $password
        ];

        if (!Auth2FA::attempt($validated, true))
        {
            return null;
        }

        $this->throttleRequestsService->clearAttempts($request);

        $tfa_pass->update([
            'used' => true,
            'password' => null,
            'salt' => null
        ]);

        $user = Auth::user();
        $validated['uuid'] = $tfa_pass->user_uuid;

        return $this->finaliseLoginProcess($user, $validated, true);
    }

    public function loginRequestAttempts(Request $request): ?array
    {
        return $this->requestAttempts($request, 'sanctumauthstarter::auth.throttle');
    }

    public function twoFactorLoginUrlHasValidUUID(string $uuid): ?array
    {
        if(!$this->getTwoFactorLoginPassword($uuid)) {
            return ['message' =>
                trans('sanctumauthstarter::auth.invalid_url')];
        }

        return null;
    }

    private function generateTwoFactorURL(Request $request, array $validated, User $user): null | ResponseData
    {
        $salt = $this->generateSalt();
        $cryptedPassword = Crypt::encryptString($validated['password'] . $salt);
        $uuid = (string) Str::uuid();

        TwoFactorLogin::firstOrCreate(
            [
                'user_uuid' => $uuid,
                'email' => $validated['email'],
                'used' => false,
            ],
            [
                'user_id' => $user->id,
                'user_uuid' => $uuid,
                'email' => $validated['email'],
                'password' => $cryptedPassword,
                'salt' => Crypt::encryptString($salt),
                'ip_address' => $this->getUserIp($request),
                'user_agent' => $request->userAgent(),
                'type' => 'twofactor',
            ]);

        $twofactor_url = route('twofactor.required', ['uuid' => $uuid,
                            'email' => $validated['email']]);

        Auth::logout();

        $data = [
            'twofactor_url' => $twofactor_url,
            'user_uuid' => $uuid,
            'access_token' => null,
        ];
        return responseData(true, Response::HTTP_OK,
            trans('sanctumauthstarter::auth.enter_2fa'), $data);
    }

    private function finaliseLoginProcess(User $user, array $validated, bool $twoFactor = false): ?array
    {
        $token = $user->createToken($validated['email']);
        $this->userLoginNotification($user, request()->ip());

        if ($twoFactor) {
            TwoFactorLoginEvent::dispatch($user, $token, $validated['uuid']);

            $data = [
                'user' => $user,
                'access_token' => null,
            ];

            return responseData(true, Response::HTTP_OK,
                trans('sanctumauthstarter::auth.success'), $data);
        }

        $data = [
            'access_token' => $token->plainTextToken,
            'user' => $user,
        ];
        return $data;
    }

    private function getTwoFactorLoginPassword(
        string $uuid,
        bool $used = false): ?TwoFactorLogin
    {
        return TwoFactorLogin::where('user_uuid', $uuid)
                    ->where('used', $used)->first();
    }
}
