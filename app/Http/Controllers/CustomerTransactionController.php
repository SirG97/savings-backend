<?php

namespace App\Http\Controllers;

use App\Enums\TransactionType;
use App\Http\Requests\CustomerTransaction\CustomerTransactionCreateRequest;
use App\Http\Requests\CustomerTransaction\CustomerTransactionDeleteRequest;
use App\Http\Requests\CustomerTransaction\CustomerTransactionReadRequest;
use App\Http\Requests\CustomerTransaction\CustomerTransactionUpdateRequest;
use App\Http\Requests\TransactionTypeReadRequest;
use App\Services\CustomerTransactionService;
use Illuminate\Http\JsonResponse;

class CustomerTransactionController extends Controller
{

    public function __construct(private CustomerTransactionService $customerTransactionService)
    { }

    /**
     * Create CustomerTransaction.
     *
     * @header Authorization Bearer {Your key}
     *
     * @bodyParam branch_id string  The branch_id of the CustomerTransaction. it is required if it is the admin making the request. Example: 1
     * @bodyParam customer_id string required The customer_id of the CustomerTransaction. . Example: 1
     * @bodyParam transaction_type string required The transaction_type of the CustomerTransaction. Can be deposit or withdrawal . Example: deposit
     * @bodyParam payment_method string required The payment_method of the CustomerTransaction. Can be cash or bank . Example: cash
     * @bodyParam amount string required The amount of the CustomerTransaction. Example: 1000
     * @bodyParam description string  The description of the CustomerTransaction. Example: Deposit from Musa
     * @bodyParam date string required The date of the CustomerTransaction. Example: 01-01-1980
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
     * @subgroup CustomerTransaction APIs
     * @group Auth APIs
     */
    public function create(CustomerTransactionCreateRequest $request): JsonResponse
    {
        return $this->_create($request, $this->customerTransactionService);
    }

    /**
     * Update CustomerTransaction.
     *
     * @header Authorization Bearer {Your key}
     *
     * @bodyParam id string required The id of the CustomerTransaction. Example: 1
     * @bodyParam name string required The name for the CustomerTransaction. Example: John
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
     * @subgroup CustomerTransaction APIs
     * @group Auth APIs
     */
    public function update(CustomerTransactionUpdateRequest $request): JsonResponse
    {
        return $this->_update($request, $this->customerTransactionService);
    }

    /**
     * Delete CustomerTransaction.
     *
     * @header Authorization Bearer {Your key}
     *
     * @bodyParam id string required The id of the CustomerTransaction. Example: 1
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
     * @subgroup CustomerTransaction APIs
     * @group Auth APIs
     */
    public function delete(CustomerTransactionDeleteRequest $request): JsonResponse
    {
        return $this->_delete($request, $this->customerTransactionService);
    }

    /**
     * Read CustomerTransaction.
     *
     * Fetch a record or records from the CustomerTransactions table.
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
     * @subgroup CustomerTransaction APIs
     * @group Auth APIs
     */
    public function read(CustomerTransactionReadRequest $request, null|string|int $id = null): JsonResponse
    {
        return $this->_read($this->customerTransactionService, $id);
    }

    /**
     * Read transaction by transaction type.
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
     * @urlParam transactionType string The transactionType of the record.Can be deposit, withdrawal, etc Example: deposit
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
    public function readByTransactionType(TransactionTypeReadRequest $request, TransactionType $transactionType, null|string|int $id = null): JsonResponse
    {
        return $this->_readByTransactionType($this->customerTransactionService, $transactionType, $id);
    }

        /**
     * Read transaction by transaction type and customer id.
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
     * @urlParam transactionType string The transactionType of the record.Can be deposit, withdrawal, etc Example: deposit
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
    public function readByTransactionTypeAndCustomer(TransactionTypeReadRequest $request, TransactionType $transactionType, int|string $customerId, null|string|int $id = null): JsonResponse
    {
        if ($data = $this->customerTransactionService->handleReadByTransactionTypeAndCustomerId($transactionType, $customerId, $id)) {
            return httpJsonResponse($data);
        }

        return unknownErrorJsonResponse();
        // return $this->_readByTransactionType($this->customerTransactionService, $transactionType, $id);
    }

           /**
     * Read transaction by transaction type and customer id.
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
     * @urlParam transactionType string The transactionType of the record.Can be deposit, withdrawal, etc Example: deposit
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
    public function readByTransactionTypeAndUser(TransactionTypeReadRequest $request, TransactionType $transactionType, int|string $userId, null|string|int $id = null): JsonResponse
    {
        if ($data = $this->customerTransactionService->handleReadByTransactionTypeAndUserId($transactionType, $userId, $id)) {
            return httpJsonResponse($data);
        }

        return unknownErrorJsonResponse();
        // return $this->_readByTransactionType($this->customerTransactionService, $transactionType, $id);
    }

               /**
     * Read transaction by transaction type and customer id.
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
     * @urlParam transactionType string The transactionType of the record.Can be deposit, withdrawal, etc Example: deposit
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
    public function readByTransactionTypeAndBranchId(TransactionTypeReadRequest $request, TransactionType $transactionType, int|string $branchId, null|string|int $id = null): JsonResponse
    {
       
        if ($data = $this->customerTransactionService->handleReadByTransactionTypeAndBranchId($transactionType, $branchId, $id)) {
            return httpJsonResponse($data);
        }

        return unknownErrorJsonResponse();
        // return $this->_readByTransactionType($this->customerTransactionService, $transactionType, $id);
    }

}
