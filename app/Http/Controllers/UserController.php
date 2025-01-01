<?php

namespace App\Http\Controllers;

use App\Enums\UserModelType;
use App\Http\Requests\User\UserAssignBranchRequest;
use App\Http\Requests\User\UserCreateRequest;
use App\Http\Requests\User\UserDeleteRequest;
use App\Http\Requests\User\UserReadRequest;
use App\Http\Requests\User\UserSuspendRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function __construct(private UserService $userService)
    { }

    /**
     * Create user.
     *
     * @header Authorization Bearer {Your key}
     *
     * @bodyParam branch_id string  The branch_id of the CustomerTransaction. it is required if it is the admin making the request. Example: 1
     * @bodyParam first_name string required The first name of the user. Example: John
     * @bodyParam last_name string required The last name of the user. Example: Doe
     * @bodyParam email string required The email of the user. Example: johndoe@xyz.com
     * @bodyParam phone string required The phone of the user. Example: 08012345678
     * @bodyParam model string required The model of user must either be super_admin, admin, auditor or marketer. Example: admin
     * @response 200
     *
     * {
     * "success": true,
     * "status_code": 200,
     * "message": string
     * "data": {}
     * }
     *
     * @authenticated
     * @subgroup Manage User APIs
     * @group Auth APIs
     */
    public function create(UserCreateRequest $request): JsonResponse
    {
        return $this->_create($request, $this->userService);
    }

    /**
     * Read user.
     *
     * Fetch a record or records from the user table.
     * The <b>id</b> param is optional but can either be a <b>string|null|int</b>
     *
     * If the <b>id</b> has a <b>null</b> value the records will be paginated.
     * The returned page size is be set from <b>api.paginate.user_kyc.pageSize</b>
     * config.
     *
     * If the <b>id</b> is a <b>string</b> value it can only be set as <b>'all'</b>.
     * This will return all the records without being paginated.
     *
     * If the <b>id</b> value is an <b>integer</b> it will try to fetch a single
     * matching record.
     *
     * @header Authorization Bearer {Your key}
     *
     * @urlParam id string The ID of the record. Example: all
     *
     * @response 200
     *
     * {
     * "success": true,
     * "status_code": 200,
     * "message": string
     * "data": {}
     * }
     *
     * @authenticated
     * @subgroup Manage User APIs
     * @group Auth APIs
     */
    public function read(UserReadRequest $request, null|string|int $id = null): JsonResponse
    {
        return $this->_read($this->userService, $id);
    }

    /**
     * Read user by user model.
     *
     * Fetch a record or records from the user table.
     * The <b>id</b> param is optional but can either be a <b>string|null|int</b>
     *
     * If the <b>id</b> has a <b>null</b> value the records will be paginated.
     * The returned page size is be set from <b>api.paginate.user.pageSize</b>
     * config.
     *
     * If the <b>id</b> is a <b>string</b> value it can only be set as <b>'all'</b>.
     * This will return all the records by user model without being paginated.
     *
     * If the <b>id</b> value is an <b>integer</b> it will try to fetch a single
     * matching record.
     *
     * @header Authorization Bearer {Your key}
     *
     * @urlParam id string The ID of the record. Example: all
     *
     * @response 200
     *
     * {
     * "success": true,
     * "status_code": 200,
     * "message": string
     * "data": {}
     * }
     *
     * @authenticated
     * @subgroup Manage User APIs
     * @group Auth APIs
     */
    public function readByUserModel(UserReadRequest $request, UserModelType $model, null|string|int $id = null): JsonResponse
    {
        return $this->_readByUserModel($this->userService, $model, $id);
    }

    /**
     * Update user.
     *
     * @header Authorization Bearer {Your key}
     *
     * @bodyParam id string required The id of the user. Example: 1
     * @bodyParam performed_action string required The action performed on the user. approved or rejected. Example: approved
     * @bodyParam reason string The is field is required if you are rejecting the kyc. Example: The image is not clear
     *
     * @response 200
     *
     * {
     * "success": true,
     * "status_code": 200,
     * "message": string
     * "data": {}
     * }
     *
     * @authenticated
     * @subgroup Manage User APIs
     * @group Auth APIs
     */
    public function update(UserUpdateRequest $request): JsonResponse
    {
        return $this->_update($request, $this->userService);
    }

    /**
     * Suspend user.
     *
     * @header Authorization Bearer {Your key}
     *
     * @bodyParam id string required The id of the user. Example: 1
     * @bodyParam active integer required The status of the user. 1 or 0. Example: 0

     *
     * @response 200
     *
     * {
     * "success": true,
     * "status_code": 200,
     * "message": string
     * "data": {}
     * }
     *
     * @authenticated
     * @subgroup Manage User APIs
     * @group Auth APIs
     */
    public function suspend(UserSuspendRequest $request): JsonResponse
    {
        return $this->_update($request, $this->userService);
    }

    /**
     * Delete user.
     *
     * @header Authorization Bearer {Your key}
     * @header X-Shipment-Mode SFN
     *
     * @bodyParam id string required The id of the user. Example: 1
     *
     * @response 200
     *
     * {
     * "success": true,
     * "status_code": 200,
     * "message": string
     * "data": {}
     * }
     *
     * @authenticated
     * @subgroup User APIs
     * @group Auth APIs
     */
    public function delete(UserDeleteRequest $request): JsonResponse
    {
        return $this->_delete($request, $this->userService);
    }

    /**
     * Assign branch to user.
     * Every staff need to be assigned a branch to work from.
     *
     * @header Authorization Bearer {Your key}
     *
     * @bodyParam id string required The id of the user. Example: 1
     * @bodyParam branch_id string required The branch_id of the branch to assign user to. Example: 1
     * @response 200
     *
     * {
     * "success": true,
     * "status_code": 200,
     * "message": string
     * "data": {}
     * }
     *
     * @authenticated
     * @subgroup Manage User APIs
     * @group Auth APIs
     */
    public function assign(UserAssignBranchRequest $request): JsonResponse
    {
        return $this->_update($request, $this->userService);
    }

}

