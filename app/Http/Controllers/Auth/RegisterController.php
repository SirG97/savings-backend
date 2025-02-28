<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\Auth\RegisterService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Ikechukwukalu\Sanctumauthstarter\Traits\Helpers;

class RegisterController extends Controller
{
    use Helpers;

    private RegisterService $registerService;

    public function __construct(RegisterService $registerService)
    {
        $this->registerService = $registerService;
        $this->middleware('guest');
    }

    /**
     * User form registration.
     *
     * Within the config file, you are required to determine whether a
     * user should recieve welcome and verification emails after
     * registration by setting <b>registration.notify.welcome</b> to <b>TRUE</b> and
     * <b>registration.notify.verify</b> to <b>TRUE</b> respectively.
     * You can also set <b>registration.autologin</b> to <b>TRUE</b>.
     *
     * @bodyParam first_name string required The first name of the user. Example: John Doe
     * @bodyParam last_name string required The full name of the user. Example: John Doe
     * @bodyParam email string required The email of the user. Example: johndoe@xyz.com
     * @bodyParam password string required The password for user authentication must contain uppercase, lowercase, symbols, numbers. Example: Ex@m122p$%l6E
     * @bodyParam password_confirmation string required Must match <small class="badge badge-blue">password</small> Field. Example: Ex@m122p$%l6E
     *
     * @response 200
     *
     * //if autologin is set to FALSE
     *
     * {
     * "status": "success",
     * "status_code": 200,
     * "data": {
     *      "message": string
     *  }
     * }
     *
     * //if autologin is set to TRUE
     *
     * {
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
    protected function register(RegisterRequest $request): JsonResponse
    {
        if ($data = $this->registerService->handleRegistration($request)) {
            return $this->httpJsonResponse(
                trans('general.success'), 200, $data);
        }

        return $this->unknownErrorResponse();
    }
}
