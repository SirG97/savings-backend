<?php

namespace App\Http\Controllers;

use App\Http\Requests\Loan\LoanCreateRequest;
use App\Http\Requests\Loan\LoanDeleteRequest;
use App\Http\Requests\Loan\LoanReadRequest;
use App\Http\Requests\Loan\LoanUpdateRequest;
use App\Services\LoanService;
use Illuminate\Http\JsonResponse;

class LoanController extends Controller
{

    public function __construct(private LoanService $loanService)
    { }

    /**
     * Create Loan.
     *
     * @header Authorization Bearer {Your key}
     *
     * @bodyParam name string required The name of the Loan. Example: John
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
     * @subgroup Loan APIs
     * @group Auth APIs
     */
    public function create(LoanCreateRequest $request): JsonResponse
    {
        return $this->_create($request, $this->loanService);
    }

    /**
     * Update Loan.
     *
     * @header Authorization Bearer {Your key}
     *
     * @bodyParam id string required The id of the Loan. Example: 1
     * @bodyParam name string required The name for the Loan. Example: John
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
     * @subgroup Loan APIs
     * @group Auth APIs
     */
    public function update(LoanUpdateRequest $request): JsonResponse
    {
        return $this->_update($request, $this->loanService);
    }

    /**
     * Delete Loan.
     *
     * @header Authorization Bearer {Your key}
     *
     * @bodyParam id string required The id of the Loan. Example: 1
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
     * @subgroup Loan APIs
     * @group Auth APIs
     */
    public function delete(LoanDeleteRequest $request): JsonResponse
    {
        return $this->_delete($request, $this->loanService);
    }

    /**
     * Read Loan.
     *
     * Fetch a record or records from the Loans table.
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
     * @subgroup Loan APIs
     * @group Auth APIs
     */
    public function read(LoanReadRequest $request, null|string|int $id = null): JsonResponse
    {
        return $this->_read($this->loanService, $id);
    }

}
