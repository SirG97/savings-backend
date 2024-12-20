<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Ikechukwukalu\Sanctumauthstarter\Traits\Helpers;
use Laravel\Socialite\Facades\Socialite;

use App\Services\Auth\SocialiteService;

class SocialiteController extends Controller
{
    use Helpers;

    private SocialiteService $socialiteService;

    public function __construct(SocialiteService $socialiteService) {
        $this->socialiteService = $socialiteService;
    }

    /**
     * User Oauth authentication redirect.
     *
     * @header Accept text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9
     * @header Content-Type text/html; charset=UTF-8
     *
     * @response 302 redirects to <small class="badge badge-blue">/auth/callback</small>
     *
     * @group Web URLs
     */
    public function authRedirect(Request $request)
    {
        $this->socialiteService->handleAuthRedirect($request);
        return Socialite::driver('google')->redirect();
    }

    /**
     * User Oauth authentication callback.
     *
     * Retrieves Oauth authenticated user details, registers and logins the
     * user.
     *
     * Retrieves <b>UUID</b> from cookie and updates the user details in the database.
     *
     * Creates user <b>access_token</b> and broadcasts the following payload <b>user_id</b>, <b>access_token</b> to the
     * unique public channel created with the unique <b>UUID</b>
     *
     * @header Accept text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*\/*;q=0.8,application/signed-exchange;v=b3;q=0.9
     * @header Content-Type text/html; charset=UTF-8
     *
     * @response 200
     *
     * {
     * "access_token": "1|mtnWTrh2Am6PWJ991wYB0rewVtROKxkuSiWEY836",
     * "user_id": 1,
     * }
     *
     * @group Web URLs
     */
    public function authCallback(Request $request)
    {
        if ($data = $this->socialiteService->loginRequestAttempts($request)) {
            return $this->socialiteService->returnTwoFactorLoginView($data);
        }

        $user = $this->socialiteService->handleAuthCallback($request);

        if (!$user) {
            $request->session()->flash('fail',
                trans('auth.failed'));
            return redirect(route('socialite.auth'));
        }

        return view('socialite.callback', ['user'
                    => $user]);
    }
}
