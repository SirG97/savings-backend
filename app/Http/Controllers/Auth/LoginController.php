<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\Auth\LoginService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Ikechukwukalu\Sanctumauthstarter\Traits\Helpers;

class LoginController extends Controller
{
    use Helpers;

    private LoginService $loginService;

    public function __construct(LoginService $loginService)
    {
        $this->loginService = $loginService;
    }

    /**
     * User form login.
     *
     * You can choose to notify a User whenever there has been a Login by setting
     * <b>password.notify.change</b> to <b>TRUE</b> Within the config file,
     *
     * Make sure to retrieve <small class="badge badge-blue">access_token</small> after login for User authentication
     *
     * @header Authorization Bearer {Your key}
     *
     * @bodyParam email string required The email of the user. Example: admin@divineglobalgrowth.com
     * @bodyParam password string required The password for user authentication must contain uppercase, lowercase, symbols, numbers. Example: password
     * @bodyParam remember_me int Could be set to 0 or 1. Example: 1
     *
     * @response 200 {
     * "status": "success",
     * "status_code": 200,
     * "data": {
     *      "message": string
     *      "access_token": string
     *  }
     * }
     *
     * @group No Auth APIs
     */
    public function login(LoginRequest $request): JsonResponse
    {
        if ($data = $this->loginService->loginRequestAttempts($request)) {
            return $this->httpJsonResponse(
                trans('sanctumauthstarter::general.fail'), 400, $data);
        }

        if ($data = $this->loginService->handleLogin($request)) {
            return $this->httpJsonResponse(
                trans('sanctumauthstarter::general.success'), 200, $data);
        }

        $data = ['message' => trans('sanctumauthstarter::auth.failed')];
        return $this->httpJsonResponse(trans('sanctumauthstarter::general.fail'), 422, $data);
    }

    /**
     * SuperAdmin form login.
     *
     * You can choose to notify a User whenever there has been a Login by setting
     * <b>password.notify.change</b> to <b>TRUE</b> Within the config file,
     *
     * Make sure to retrieve <small class="badge badge-blue">access_token</small> after login for User authentication
     *
     * @header Authorization Bearer {Your key}
     *
     * @bodyParam email string required The email of the user. Example: admin@divineglobalgrowth.com
     * @bodyParam password string required The password for user authentication must contain uppercase, lowercase, symbols, numbers. Example: password
     * @bodyParam remember_me int Could be set to 0 or 1. Example: 1
     *
     * @response 200 {
     * "status": "success",
     * "status_code": 200,
     * "data": {
     *      "message": string
     *      "access_token": string
     *  }
     * }
     *
     * @group No Auth APIs
     */
    public function superAdminLogin(LoginRequest $request): JsonResponse
    {
        if ($data = $this->loginService->loginRequestAttempts($request)) {
            return $this->httpJsonResponse(
                trans('sanctumauthstarter::general.fail'), 400, $data);
        }

        if ($data = $this->loginService->handleSuperAdminLogin($request)) {
            return $this->httpJsonResponse(
                trans('sanctumauthstarter::general.success'), 200, $data);
        }

        $data = ['message' => trans('sanctumauthstarter::auth.failed')];
        return $this->httpJsonResponse(trans('sanctumauthstarter::general.fail'), 422, $data);
    }

    /**
     * Admin form login.
     *
     * You can choose to notify a User whenever there has been a Login by setting
     * <b>password.notify.change</b> to <b>TRUE</b> Within the config file,
     *
     * Make sure to retrieve <small class="badge badge-blue">access_token</small> after login for User authentication
     *
     * @header Authorization Bearer {Your key}
     *
     * @bodyParam email string required The email of the user. Example: admin@divineglobalgrowth.com
     * @bodyParam password string required The password for user authentication must contain uppercase, lowercase, symbols, numbers. Example: password
     * @bodyParam remember_me int Could be set to 0 or 1. Example: 1
     *
     * @response 200 {
     * "status": "success",
     * "status_code": 200,
     * "data": {
     *      "message": string
     *      "access_token": string
     *  }
     * }
     *
     * @group No Auth APIs
     */
    public function adminLogin(LoginRequest $request): JsonResponse
    {
        if ($data = $this->loginService->loginRequestAttempts($request)) {
            return $this->httpJsonResponse(
                trans('sanctumauthstarter::general.fail'), 400, $data);
        }

        if ($data = $this->loginService->handleAdminLogin($request)) {
            return $this->httpJsonResponse(
                trans('sanctumauthstarter::general.success'), 200, $data);
        }

        $data = ['message' => trans('sanctumauthstarter::auth.failed')];
        return $this->httpJsonResponse(trans('sanctumauthstarter::general.fail'), 422, $data);
    }

    /**
     * User Two-factor login.
     *
     * Presents a form were a user must input the generated code.
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
    public function twoFactorLogin(Request $request)
    {
        if ($data = $this->loginService->loginRequestAttempts($request)) {
            return $this->loginService->returnTwoFactorLoginView($data);
        }

        if ($data = $this->loginService->twoFactorLoginUrlHasValidUUID($request->uuid)) {
            return $this->loginService->returnTwoFactorLoginView($data);
        }

        if ($data = $this->loginService->handleTwoFactorLogin($request)) {
            return view('sanctumauthstarter::twofactor.callback', $data);
        }

        return $this->loginService->returnTwoFactorLoginView();
    }
}
