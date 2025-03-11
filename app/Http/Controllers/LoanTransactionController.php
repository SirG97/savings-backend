<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoanTransactionCreateRequest;
use App\Http\Requests\LoanTransactionDeleteRequest;
use App\Http\Requests\LoanTransactionReadRequest;
use App\Http\Requests\LoanTransactionUpdateRequest;
use App\Services\LoanTransactionService;
use Illuminate\Http\JsonResponse;

class LoanTransactionController extends Controller
{

    public function __construct(private LoanTransactionService $loanTransactionService)
    { }

    /**
     * Create LoanTransaction.
     *
     * @header Authorization Bearer {Your key}
     *
     * @bodyParam name string required The name of the LoanTransaction. Example: John
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
     * @subgroup LoanTransaction APIs
     * @group Auth APIs
     */
    public function create(LoanTransactionCreateRequest $request): JsonResponse
    {
        return $this->_create($request, $this->loanTransactionService);
    }

    /**
     * Update LoanTransaction.
     *
     * @header Authorization Bearer {Your key}
     *
     * @bodyParam id string required The id of the LoanTransaction. Example: 1
     * @bodyParam name string required The name for the LoanTransaction. Example: John
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
     * @subgroup LoanTransaction APIs
     * @group Auth APIs
     */
    public function update(LoanTransactionUpdateRequest $request): JsonResponse
    {
        return $this->_update($request, $this->loanTransactionService);
    }

    /**
     * Delete LoanTransaction.
     *
     * @header Authorization Bearer {Your key}
     *
     * @bodyParam id string required The id of the LoanTransaction. Example: 1
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
     * @subgroup LoanTransaction APIs
     * @group Auth APIs
     */
    public function delete(LoanTransactionDeleteRequest $request): JsonResponse
    {
        return $this->_delete($request, $this->loanTransactionService);
    }

    /**
     * Read LoanTransaction.
     *
     * Fetch a record or records from the LoanTransactions table.
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
     * @subgroup LoanTransaction APIs
     * @group Auth APIs
     */
    public function read(LoanTransactionReadRequest $request, null|string|int $id = null): JsonResponse
    {
        return $this->_read($this->loanTransactionService, $id);
    }

}
