<?php

namespace App\Http\Controllers;

use App\Enums\TransactionType;
use App\Http\Requests\Transaction\TransactionCreateRequest;
use App\Http\Requests\Transaction\TransactionDeleteRequest;
use App\Http\Requests\Transaction\TransactionReadRequest;
use App\Http\Requests\Transaction\TransactionUpdateRequest;
use App\Http\Requests\TransactionTypeReadRequest;
use App\Services\TransactionService;
use Illuminate\Http\JsonResponse;

class TransactionController extends Controller
{

    public function __construct(private TransactionService $transactionService)
    { }

    /**
     * Create Transaction.
     *
     * @header Authorization Bearer {Your key}
     *
     * @bodyParam branch_id string  The branch_id of the Transaction. it is required if it is the admin making the request. Example: 1
     * @bodyParam customer_id string required The customer_id of the Transaction. . Example: 1
     * @bodyParam transaction_type string required The transaction_type of the Transaction. Can be deposit or withdrawal . Example: deposit
     * @bodyParam payment_method string required The payment_method of the Transaction. Can be cash or bank . Example: cash
     * @bodyParam amount string required The amount of the Transaction. Example: 1000
     * @bodyParam description string  The description of the Transaction. Example: Deposit from Musa
     * @bodyParam date string required The date of the Transaction. Example: 01-01-1980
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
     * @subgroup Transaction APIs
     * @group Auth APIs
     */
    public function create(TransactionCreateRequest $request): JsonResponse
    {
        return $this->_create($request, $this->transactionService);
    }

    /**
     * Update Transaction.
     *
     * @header Authorization Bearer {Your key}
     *
     * @bodyParam id string required The id of the Transaction. Example: 1
     * @bodyParam name string required The name for the Transaction. Example: John
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
     * @subgroup Transaction APIs
     * @group Auth APIs
     */
    public function update(TransactionUpdateRequest $request): JsonResponse
    {
        return $this->_update($request, $this->transactionService);
    }

    /**
     * Delete Transaction.
     *
     * @header Authorization Bearer {Your key}
     *
     * @bodyParam id string required The id of the Transaction. Example: 1
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
     * @subgroup Transaction APIs
     * @group Auth APIs
     */
    public function delete(TransactionDeleteRequest $request): JsonResponse
    {
        return $this->_delete($request, $this->transactionService);
    }

    /**
     * Read Transaction.
     *
     * Fetch a record or records from the Transactions table.
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
     * @subgroup Transaction APIs
     * @group Auth APIs
     */
    public function read(TransactionReadRequest $request, null|string|int $id = null): JsonResponse
    {
        return $this->_read($this->transactionService, $id);
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
        return $this->_readByTransactionType($this->transactionService, $transactionType, $id);
    }

    /**
     * Read transaction by transaction type and branchId.
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
    public function readByTransactionTypeAndBranchId(TransactionTypeReadRequest $request, TransactionType $transactionType, int $branchId, null|string|int $id = null): JsonResponse
    {
        return $this->_readByTransactionTypeAndBranchId($this->transactionService, $transactionType, $branchId, $id);
    }

        /**
     * Read transaction by transaction type and branchId.
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
    public function readByTransactionTypeAndUserId(TransactionTypeReadRequest $request, TransactionType $transactionType, int $branchId, null|string|int $id = null): JsonResponse
    {
        return $this->_readByTransactionTypeAndUserId($this->transactionService, $transactionType, $branchId, $id);
    }

}
