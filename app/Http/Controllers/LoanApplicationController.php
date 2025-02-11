<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoanApplication\LoanApplicationCreateRequest;
use App\Http\Requests\LoanApplication\LoanApplicationDeleteRequest;
use App\Http\Requests\LoanApplication\LoanApplicationReadRequest;
use App\Http\Requests\LoanApplication\LoanApplicationUpdateRequest;
use App\Services\LoanApplicationService;
use Illuminate\Http\JsonResponse;

class LoanApplicationController extends Controller
{

    public function __construct(private LoanApplicationService $loanApplicationService)
    { }

    /**
     * Create LoanApplication.
     *
     * @header Authorization Bearer {Your key}
     *
     * @bodyParam name string required The name of the LoanApplication. Example: John
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
     * @subgroup LoanApplication APIs
     * @group Auth APIs
     */
    public function create(LoanApplicationCreateRequest $request): JsonResponse
    {
        return $this->_create($request, $this->loanApplicationService);
    }

    /**
     * Update LoanApplication.
     *
     * @header Authorization Bearer {Your key}
     *
     * @bodyParam id string required The id of the LoanApplication. Example: 1
     * @bodyParam name string required The name for the LoanApplication. Example: John
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
     * @subgroup LoanApplication APIs
     * @group Auth APIs
     */
    public function update(LoanApplicationUpdateRequest $request): JsonResponse
    {
        return $this->_update($request, $this->loanApplicationService);
    }

    /**
     * Delete LoanApplication.
     *
     * @header Authorization Bearer {Your key}
     *
     * @bodyParam id string required The id of the LoanApplication. Example: 1
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
     * @subgroup LoanApplication APIs
     * @group Auth APIs
     */
    public function delete(LoanApplicationDeleteRequest $request): JsonResponse
    {
        return $this->_delete($request, $this->loanApplicationService);
    }

    /**
     * Read LoanApplication.
     *
     * Fetch a record or records from the LoanApplications table.
     * The <b>id</b> param is optional but can either be a <b>string|null|int</b>
     *
     * If the <b>id</b> has a <b>null</b> value the records will be paginated.
     * The returned page size is be set from <b>api.paginate.user_address.pageSize</b>
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
     * @subgroup LoanApplication APIs
     * @group Auth APIs
     */
    public function read(LoanApplicationReadRequest $request, null|string|int $id = null): JsonResponse
    {
        return $this->_read($this->loanApplicationService, $id);
    }

    /**
     * Read LoanApplication by customer id.
     *
     * Fetch a record or records from the LoanApplications table.
     * The <b>id</b> param is optional but can either be a <b>string|null|int</b>
     *
     * If the <b>id</b> has a <b>null</b> value the records will be paginated.
     * The returned page size is be set from <b>api.paginate.user_address.pageSize</b>
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
     * @subgroup LoanApplication APIs
     * @group Auth APIs
     */
    public function readByCustomerId(LoanApplicationReadRequest $request, null|string|int $id = null): JsonResponse
    {
        return $this->_readByCustomerId($this->loanApplicationService, $id);
    }

    /**
     * Read LoanApplication by user id.
     *
     * Fetch a record or records from the LoanApplications table.
     * The <b>id</b> param is optional but can either be a <b>string|null|int</b>
     *
     * If the <b>id</b> has a <b>null</b> value the records will be paginated.
     * The returned page size is be set from <b>api.paginate.user_address.pageSize</b>
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
     * @subgroup LoanApplication APIs
     * @group Auth APIs
     */
    public function readByUserId(LoanApplicationReadRequest $request, null|string|int $id = null): JsonResponse
    {
        return $this->_readByUserId($this->loanApplicationService, $id);
    }

    /**
     * Read LoanApplication by branch id.
     *
     * Fetch a record or records from the LoanApplications table.
     * The <b>id</b> param is optional but can either be a <b>string|null|int</b>
     *
     * If the <b>id</b> has a <b>null</b> value the records will be paginated.
     * The returned page size is be set from <b>api.paginate.user_address.pageSize</b>
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
     * @subgroup LoanApplication APIs
     * @group Auth APIs
     */
    public function readByBranchId(LoanApplicationReadRequest $request, null|string|int $id = null): JsonResponse
    {
        return $this->_readByBranchId($this->loanApplicationService, $id);
    }

}
