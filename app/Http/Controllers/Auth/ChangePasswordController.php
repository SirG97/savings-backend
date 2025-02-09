<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Services\Auth\ChangePasswordService;
use Ikechukwukalu\Sanctumauthstarter\Traits\Helpers;
use Illuminate\Http\JsonResponse;

class ChangePasswordController extends Controller
{
    use Helpers;

    private ChangePasswordService $changePasswordService;

    public function __construct(ChangePasswordService $changePasswordService)
    {
        $this->changePasswordService = $changePasswordService;
    }

    /**
     * User change password.
     *
     * Within the config file, you are required to determine the number
     * of previously used passwords a User is not allowed to use anymore
     * by setting <b>password.check_all</b> to <b>TRUE/FALSE</b> or to an <b>int</b>
     * value and <b>password.number</b> to a corresponding <b>int</b>
     * value as well.
     *
     * You can choose to notify a User whenever a password is changed by setting
     * <b>password.notify.change</b> to <b>TRUE</b>
     *
     * @header Authorization Bearer {Your key}
     *
     * @bodyParam current_password string required The user's password. Example: Ex@m122p$%l6E
     * @bodyParam password string required The password for user authentication must contain uppercase, lowercase, symbols, numbers. Example: @wE3456qas@$
     * @bodyParam password_confirmation string required Must match <small class="badge badge-blue">password</small> Field. Example: @wE3456qas@$
     *
     * @response 200
     *
     * {
     * "status": "success",
     * "status_code": 200,
     * "data": {
     *      "message": string
     *  }
     * }
     *
     * @authenticated
     * @group Auth APIs
     */
    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {

        if ($data = $this->changePasswordService->handlePasswordChange($request))
        {
            return $this->httpJsonResponse(
                trans('general.success'), 200, $data);
        }

        return $this->unknownErrorResponse();
    }
}
