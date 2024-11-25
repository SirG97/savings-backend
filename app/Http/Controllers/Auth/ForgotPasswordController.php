<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Services\Auth\ForgotPasswordService;
use Ikechukwukalu\Sanctumauthstarter\Traits\Helpers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    use Helpers;

    private ForgotPasswordService $forgotPasswordService;

    public function __construct(ForgotPasswordService $forgotPasswordService)
    {
        $this->middleware('guest');
        $this->forgotPasswordService = $forgotPasswordService;
    }


    /**
     * User forgot password.
     *
     * The user must enter a registered email.
     *
     * @bodyParam email string required The email of the user. Example: johndoe@xyz.com
     *
     * @response 200 {
     * "status": "success",
     * "status_code": 200,
     * "data": {
     *      "message": string
     *  }
     * }
     *
     * @group No Auth APIs
     */
    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $data = $this->forgotPasswordService->handleForgotPassword($request);
        return $this->httpJsonResponse(trans('general.success'), 200, $data);
    }
}
