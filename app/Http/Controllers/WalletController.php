<?php

namespace App\Http\Controllers;

use App\Http\Requests\WalletCreateRequest;
use App\Http\Requests\WalletDeleteRequest;
use App\Http\Requests\WalletReadRequest;
use App\Http\Requests\WalletUpdateRequest;
use App\Services\WalletService;
use Illuminate\Http\JsonResponse;

class WalletController extends Controller
{

    public function __construct(private WalletService $walletService)
    { }

    /**
     * Create Wallet.
     *
     * @header Authorization Bearer {Your key}
     *
     * @bodyParam name string required The name of the Wallet. Example: John
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
     * @subgroup Wallet APIs
     * @group Auth APIs
     */
    public function create(WalletCreateRequest $request): JsonResponse
    {
        return $this->_create($request, $this->walletService);
    }

    /**
     * Update Wallet.
     *
     * @header Authorization Bearer {Your key}
     *
     * @bodyParam id string required The id of the Wallet. Example: 1
     * @bodyParam name string required The name for the Wallet. Example: John
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
     * @subgroup Wallet APIs
     * @group Auth APIs
     */
    public function update(WalletUpdateRequest $request): JsonResponse
    {
        return $this->_update($request, $this->walletService);
    }

    /**
     * Delete Wallet.
     *
     * @header Authorization Bearer {Your key}
     *
     * @bodyParam id string required The id of the Wallet. Example: 1
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
     * @subgroup Wallet APIs
     * @group Auth APIs
     */
    public function delete(WalletDeleteRequest $request): JsonResponse
    {
        return $this->_delete($request, $this->walletService);
    }

    /**
     * Read Wallet.
     *
     * Fetch a record or records from the Wallets table.
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
     * @subgroup Wallet APIs
     * @group Auth APIs
     */
    public function read(WalletReadRequest $request, null|string|int $id = null): JsonResponse
    {
        return $this->_read($this->walletService, $id);
    }

}
