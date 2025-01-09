<?php

namespace App\Http\Controllers;

use App\Http\Requests\DashboardRequest;
use App\Services\DashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(private DashboardService $dashboardService)
    {

    }

    /**
     * Retrieve dashboard.
     *
     * @header Authorization Bearer {Your key}
     *
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
     * @subgroup Dashboard APIs
     * @group Auth APIs
     */
    public function read(DashboardRequest $request, null|string|int $id = null): JsonResponse
    {
        if ($response = $this->dashboardService->getDashboardData($request, $id)) {
            return httpJsonResponse($response);
        }

        return unknownErrorJsonResponse();

    }

    /**
     * Retrieve dashboard.
     *
     * @header Authorization Bearer {Your key}
     *
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
     * @subgroup Dashboard APIs
     * @group Auth APIs
     */
    public function readByUserId(DashboardRequest $request, null|string|int $id = null): JsonResponse
    {
        if ($response = $this->dashboardService->getDashboardDataByUserId($request, $id)) {
            return httpJsonResponse($response);
        }

        return unknownErrorJsonResponse();

    }


    /**
     * Retrieve dashboard.
     *
     * @header Authorization Bearer {Your key}
     *
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
     * @subgroup Dashboard APIs
     * @group Auth APIs
     */
    public function readByCustomerId(DashboardRequest $request, null|string|int $id = null): JsonResponse
    {
        if ($response = $this->dashboardService->getDashboardDataByCustomerId($request, $id)) {
            return httpJsonResponse($response);
        }

        return unknownErrorJsonResponse();

    }
}
