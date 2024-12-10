<?php

namespace App\Http\Controllers;

use App\Http\Requests\Branch\BranchCreateRequest;
use App\Http\Requests\Branch\BranchDeleteRequest;
use App\Http\Requests\Branch\BranchReadRequest;
use App\Http\Requests\Branch\BranchUpdateRequest;
use App\Services\BranchService;
use Illuminate\Http\JsonResponse;

class BranchController extends Controller
{

    public function __construct(private BranchService $branchService)
    { }

    /**
     * Create Branch.
     *
     * @header Authorization Bearer {Your key}
     *
     * @bodyParam name string required The name of the Branch. Example: Nkpor
     * @bodyParam address string required The address of the Branch. Example: No 12 Oguta Road, Onitsha
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
     * @subgroup Branch APIs
     * @group Auth APIs
     */
    public function create(BranchCreateRequest $request): JsonResponse
    {
        return $this->_create($request, $this->branchService);
    }

    /**
     * Update Branch.
     *
     * @header Authorization Bearer {Your key}
     *
     * @bodyParam id string required The id of the Branch. Example: 1
     * @bodyParam name The name for the Branch. Example: John
     * @bodyParam address The address of the Branch. Example: No 31 Isunjaba Street, Awada
     * @bodyParam active  The status of the Branch. Example: 0 or 1
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
     * @subgroup Branch APIs
     * @group Auth APIs
     */
    public function update(BranchUpdateRequest $request): JsonResponse
    {
        return $this->_update($request, $this->branchService);
    }

    /**
     * Delete Branch.
     *
     * @header Authorization Bearer {Your key}
     *
     * @bodyParam id string required The id of the Branch. Example: 1
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
     * @subgroup Branch APIs
     * @group Auth APIs
     */
    public function delete(BranchDeleteRequest $request): JsonResponse
    {
        return $this->_delete($request, $this->branchService);
    }

    /**
     * Read Branch.
     *
     * Fetch a record or records from the Branchs table.
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
     * @subgroup Branch APIs
     * @group Auth APIs
     */
    public function read(BranchReadRequest $request, null|string|int $id = null): JsonResponse
    {
        return $this->_read($this->branchService, $id);
    }

}
