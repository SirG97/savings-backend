<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Services\SearchService;
use Illuminate\Http\JsonResponse;

class SearchController extends Controller
{
    //
    public function __construct(private SearchService $searchService)
    { }

    /**
     * Search Customer.
     *
     * @header Authorization Bearer {Your key}
     *
     * @urlParam $value string The value being searched for. Example: Live horse
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
     * @subgroup Search APIs
     * @group Auth APIs
     */
    public function customer(SearchRequest $request, string $value): JsonResponse
    {
        if ($data = $this->searchService->handleCustomerSearch($value)) {
            return httpJsonResponse($data);
        }

        return unknownErrorJsonResponse();
    }

    /**
     * Search Customer by branch id.
     *
     * @header Authorization Bearer {Your key}
     *
     * @urlParam $value string The value being searched for. Example: Live horse
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
     * @subgroup Search APIs
     * @group Auth APIs
     */
    public function customerByBranchId(SearchRequest $request, string $value): JsonResponse
    {
        if ($data = $this->searchService->handleCustomerSearch($value)) {
            return httpJsonResponse($data);
        }

        return unknownErrorJsonResponse();
    }

    /**
     * Search Customer by branch id.
     *
     * @header Authorization Bearer {Your key}
     *
     * @urlParam $value string The value being searched for. Example: Live horse
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
     * @subgroup Search APIs
     * @group Auth APIs
     */
    public function customerByBranchIdAndUserId(SearchRequest $request, string $value): JsonResponse
    {
        if ($data = $this->searchService->handleCustomerSearch($value)) {
            return httpJsonResponse($data);
        }

        return unknownErrorJsonResponse();
    }

    /**
     * Search user.
     *
     * @header Authorization Bearer {Your key}
     *
     * @urlParam $value string The value being searched for. Example: Live horse
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
     * @subgroup Search APIs
     * @group Auth APIs
     */
    public function user(SearchRequest $request, string $value): JsonResponse
    {
        if ($data = $this->searchService->handleUserSearch($value)) {
            return httpJsonResponse($data);
        }

        return unknownErrorJsonResponse();
    }

    /**
     * Search customerTransaction.
     *
     * @header Authorization Bearer {Your key}
     *
     * @urlParam $value string The value being searched for. Example: Live horse
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
     * @subgroup Search APIs
     * @group Auth APIs
     */
    public function customerTransaction(SearchRequest $request, string $value): JsonResponse
    {
        if ($data = $this->searchService->handleCustomerTransactionSearch($value)) {
            return httpJsonResponse($data);
        }

        return unknownErrorJsonResponse();
    }


    /**
     * Search Transaction.
     *
     * @header Authorization Bearer {Your key}
     *
     * @urlParam $value string The value being searched for. Example: Live horse
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
     * @subgroup Search APIs
     * @group Auth APIs
     */
    public function transaction(SearchRequest $request, string $value): JsonResponse
    {
        if ($data = $this->searchService->handleTransactionSearch($value)) {
            return httpJsonResponse($data);
        }

        return unknownErrorJsonResponse();
    }

}
