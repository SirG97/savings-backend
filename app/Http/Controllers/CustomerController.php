<?php

namespace App\Http\Controllers;

use App\Http\Requests\Customer\CustomerCreateRequest;
use App\Http\Requests\Customer\CustomerDeleteRequest;
use App\Http\Requests\Customer\CustomerReadRequest;
use App\Http\Requests\Customer\CustomerUpdateRequest;
use App\Services\CustomerService;
use Illuminate\Http\JsonResponse;

class CustomerController extends Controller
{

    public function __construct(private CustomerService $customerService)
    { }

    /**
     * Create Customer.
     *
     * @header Authorization Bearer {Your key}
     *
     * @bodyParam name string required The name of the Customer. Example: John
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
     * @subgroup Customer APIs
     * @group Auth APIs
     */
    public function create(CustomerCreateRequest $request): JsonResponse
    {
        return $this->_create($request, $this->customerService);
    }

    /**
     * Update Customer.
     *
     * @header Authorization Bearer {Your key}
     *
     * @bodyParam id string required The id of the Customer. Example: 1
     * @bodyParam name string required The name for the Customer. Example: John
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
     * @subgroup Customer APIs
     * @group Auth APIs
     */
    public function update(CustomerUpdateRequest $request): JsonResponse
    {
        return $this->_update($request, $this->customerService);
    }

    /**
     * Delete Customer.
     *
     * @header Authorization Bearer {Your key}
     *
     * @bodyParam id string required The id of the Customer. Example: 1
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
     * @subgroup Customer APIs
     * @group Auth APIs
     */
    public function delete(CustomerDeleteRequest $request): JsonResponse
    {
        return $this->_delete($request, $this->customerService);
    }

    /**
     * Read Customer.
     *
     * Fetch a record or records from the Customers table.
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
     * @subgroup Customer APIs
     * @group Auth APIs
     */
    public function read(CustomerReadRequest $request, null|string|int $id = null): JsonResponse
    {
        return $this->_read($this->customerService, $id);
    }

}