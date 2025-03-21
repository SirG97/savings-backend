<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ConFirmTwoFactorRequest;
use App\Services\Auth\TwoFactorService;
use Ikechukwukalu\Sanctumauthstarter\Traits\Helpers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwoFactorController extends Controller
{
    use Helpers;

    private TwoFactorService $twoFactorService;

    public function __construct(TwoFactorService $twoFactorService)
    {
        $this->twoFactorService = $twoFactorService;
        $this->middleware('auth:sanctum');
    }

    /**
     * Create 2FA.
     *
     * To enable Two-Factor Authentication for the User,
     * he/she must sync the Shared Secret between its Authenticator
     * app and the application.
     *
     * @header Authorization Bearer {Your key}
     *
     * @response 200
     *
     * {
     * "status": "success",
     * "status_code": 200,
     * "data": {
     *      "qr_code": string,
     *      "uri": string,
     *      "string": string,
     *  }
     * }
     *
     * @authenticated
     * @group Auth APIs
     */
    public function createTwoFactor(Request $request): JsonResponse
    {
        if ($data = $this->twoFactorService->handleCreateTwoFactor($request)) {
            return $this->httpJsonResponse(
                    trans('general.success'), 200, $data);
        }

        return $this->unknownErrorResponse();
    }

    /**
     * Confirm 2FA.
     *
     * Recovery codes will be generated if code is invalid.
     *
     * @header Authorization Bearer {Your key}
     *
     * @bodyParam code numeric required The authenticator code. Example: 123 456
     *
     * @response 200
     *
     * {
     * "status": "success",
     * "status_code": 200,
     * "data": {
     *      "message": string,
     *      "codes": array,
     *  }
     * }
     *
     * @authenticated
     * @group Auth APIs
     */
    public function confirmTwoFactor(ConFirmTwoFactorRequest $request): JsonResponse
    {
        if ($this->twoFactorService->isTwoFactorEnabled()) {
            $data = [
                'message' => trans('auth.2fa_is_enabled'),
                'codes' => []
            ];

            return $this->httpJsonResponse(
                    trans('general.fail'), 400, $data);
        }

        if ($data = $this->twoFactorService->handleConFirmTwoFactor($request))
        {
            return $this->httpJsonResponse(
                    trans('general.success'), 200, $data);
        }

        $data = [
            'message' => trans('auth.wrong_code'),
            'codes' => []
        ];

        return $this->httpJsonResponse(
                trans('general.fail'), 400, $data);

    }

    /**
     * Disable 2FA.
     *
     * To disable Two-Factor Authentication for the User.
     *
     * @header Authorization Bearer {Your key}
     *
     * @response 200
     *
     * {
     * "status": "success",
     * "status_code": 200,
     * "data": {
     *      "message": string,
     *  }
     * }
     *
     * @authenticated
     * @group Auth APIs
     */
    public function disableTwoFactor(Request $request): JsonResponse
    {
        $data = $this->twoFactorService->handleDisableTwoFactor($request);
        return $this->httpJsonResponse(
                    trans('general.success'), 200, $data);
    }

    /**
     * Get 2FA recovery codes.
     *
     * The User can retrieve current recovery codes.
     *
     * @header Authorization Bearer {Your key}
     *
     * @response 200
     *
     * {
     * "status": "success",
     * "status_code": 200,
     * "data": {
     *      "message": string,
     *      "codes": array,
     *  }
     * }
     *
     * @authenticated
     * @group Auth APIs
     */
    public function currentRecoveryCodes(Request $request)
    {
        if ($data = $this->twoFactorService->handleCurrentRecoveryCodes($request))
        {
            return $this->httpJsonResponse(
                    trans('general.success'), 200, $data);
        }

        return $this->unknownErrorResponse();
    }

    /**
     * New 2FA recovery codes.
     *
     * The User can generate a fresh batch of codes which replaces
     * the previous batch.
     *
     * @header Authorization Bearer {Your key}
     *
     * @response 200
     *
     * {
     * "status": "success",
     * "status_code": 200,
     * "data": {
     *      "message": string,
     *      "codes": array,
     *  }
     * }
     *
     * @authenticated
     * @group Auth APIs
     */
    public function newRecoveryCodes(Request $request)
    {
        if ($data = $this->twoFactorService->handleNewRecoveryCodes($request))
        {
            return $this->httpJsonResponse(
                    trans('general.success'), 200, $data);
        }

        return $this->unknownErrorResponse();
    }
}
