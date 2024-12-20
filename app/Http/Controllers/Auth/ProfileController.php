<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\EditProfileRequest;
use App\Services\Auth\EditProfileService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Ikechukwukalu\Sanctumauthstarter\Traits\Helpers;

class ProfileController extends Controller
{
    use Helpers;

    private EditProfileService $editProfileService;

    public function __construct(EditProfileService $editProfileService)
    {
        $this->editProfileService = $editProfileService;
    }

    /**
     * User edit profile.
     *
     * @header Authorization Bearer {Your key}
     *
     * @bodyParam name string required The user fullname. Example: John Doe
     * @bodyParam email string required The user email. Example: johndoe@example.com
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

    public function editProfile(EditProfileRequest $request): JsonResponse
    {
        if ($data = $this->editProfileService->handleEditProfile($request)) {
            return $this->httpJsonResponse(
                trans('general.success'), 200, $data);
        }

        return $this->unknownErrorResponse();
    }
}
