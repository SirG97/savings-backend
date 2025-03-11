<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerLoanTransactionCreateRequest;
use App\Http\Requests\CustomerLoanTransactionDeleteRequest;
use App\Http\Requests\CustomerLoanTransactionReadRequest;
use App\Http\Requests\CustomerLoanTransactionUpdateRequest;
use App\Services\CustomerLoanTransactionService;
use Illuminate\Http\JsonResponse;

class CustomerLoanTransactionController extends Controller
{

    public function __construct(private CustomerLoanTransactionService $customerLoanTransactionService)
    { }

    /**
     * Create CustomerLoanTransaction.
     *
     * @header Authorization Bearer {Your key}
     *
     * @bodyParam name string required The name of the CustomerLoanTransaction. Example: John
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
     * @subgroup CustomerLoanTransaction APIs
     * @group Auth APIs
     */
    public function create(CustomerLoanTransactionCreateRequest $request): JsonResponse
    {
        return $this->_create($request, $this->customerLoanTransactionService);
    }

    /**
     * Update CustomerLoanTransaction.
     *
     * @header Authorization Bearer {Your key}
     *
     * @bodyParam id string required The id of the CustomerLoanTransaction. Example: 1
     * @bodyParam name string required The name for the CustomerLoanTransaction. Example: John
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
     * @subgroup CustomerLoanTransaction APIs
     * @group Auth APIs
     */
    public function update(CustomerLoanTransactionUpdateRequest $request): JsonResponse
    {
        return $this->_update($request, $this->customerLoanTransactionService);
    }

    /**
     * Delete CustomerLoanTransaction.
     *
     * @header Authorization Bearer {Your key}
     *
     * @bodyParam id string required The id of the CustomerLoanTransaction. Example: 1
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
     * @subgroup CustomerLoanTransaction APIs
     * @group Auth APIs
     */
    public function delete(CustomerLoanTransactionDeleteRequest $request): JsonResponse
    {
        return $this->_delete($request, $this->customerLoanTransactionService);
    }

    /**
     * Read CustomerLoanTransaction.
     *
     * Fetch a record or records from the CustomerLoanTransactions table.
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
     * @subgroup CustomerLoanTransaction APIs
     * @group Auth APIs
     */
    public function read(CustomerLoanTransactionReadRequest $request, null|string|int $id = null): JsonResponse
    {
        return $this->_read($this->customerLoanTransactionService, $id);
    }

}
