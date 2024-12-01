<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerWallet\CustomerWalletReadRequest;
use App\Services\CustomerWalletService;
use Illuminate\Http\JsonResponse;

class CustomerWalletController extends Controller
{

    public function __construct(private CustomerWalletService $customerWalletService)
    { }



    /**
     * Read CustomerWallet.
     *
     * Fetch a record or records from the CustomerWallets table.
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
     * @subgroup CustomerWallet APIs
     * @group Auth APIs
     */
    public function read(CustomerWalletReadRequest $request, null|string|int $id = null): JsonResponse
    {
        return $this->_read($this->customerWalletService, $id);
    }

}
