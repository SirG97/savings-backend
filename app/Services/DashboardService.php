<?php

namespace App\Services;

use App\Actions\ResponseData;
use App\Contracts\DashboardRepositoryInterface;
use App\Http\Requests\DashboardRequest;
use App\Models\SuperAdmin;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class DashboardService extends BasicCrudService
{
    public function __construct(private DashboardRepositoryInterface $dashboardRepository)
    { }

    /**
     * Handle the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return null|array
     */
    public function getDashboardData(DashboardRequest $request, $id): ResponseData
    {
        if(!$id and Auth::user()->model !== SuperAdmin::class ){
            $id = Auth::user()->branch_id;
        }
        $totalUsers = $this->dashboardRepository->getTotalUsers($id);
        $balance = $this->dashboardRepository->getTotalBalance($id);
        $filters = [];
        $transactionSummary = $this->dashboardRepository->getTransactionSummaryByType($filters, $id);

        $data = [
            'total_users' => $totalUsers,
            'balance' => $balance,
            'transaction_summary' => [
                'deposit' => [
                    'count' => $transactionSummary['deposit']['count'] ?? 0,
                    'total_amount' => $transactionSummary['deposit']['total_amount'] ?? 0,
                ],
                'withdrawals' => [
                    'count' => $transactionSummary['withdrawal']['count'] ?? 0,
                    'total_amount' => $transactionSummary['withdrawal']['total_amount'] ?? 0,
                ],
                'commission' => [
                    'count' => $transactionSummary['commission']['count'] ?? 0,
                    'total_amount' => $transactionSummary['commission']['total_amount'] ?? 0,
                ],
                'expenses' => [
                    'count' => $transactionSummary['expenses']['count'] ?? 0,
                    'total_amount' => $transactionSummary['expenses']['total_amount'] ?? 0,
                ],
                'transfer' => [
                    'count' => $transactionSummary['transfer']['count'] ?? 0,
                    'total_amount' => $transactionSummary['transfer']['total_amount'] ?? 0,
                ],
            ],
        ];

        return responseData(true, Response::HTTP_OK,
            'Dashboard data retrieved successfully', $data);
    }

    public function getDashboardDataByBranch(DashboardRequest $request): ResponseData
    {
        $totalUsers = $this->dashboardRepository->getTotalUsers();
        $balance = $this->dashboardRepository->getTotalBalance();
        $filters = [];
        $transactionSummary = $this->dashboardRepository->getTransactionSummaryByType($filters);

        $data = [
            'total_users' => $totalUsers,
            'balance' => $balance,
            'transaction_summary' => [
                'deposit' => [
                    'count' => $transactionSummary['deposit']['count'] ?? 0,
                    'total_amount' => $transactionSummary['deposit']['total_amount'] ?? 0,
                ],
                'withdrawals' => [
                    'count' => $transactionSummary['withdrawal']['count'] ?? 0,
                    'total_amount' => $transactionSummary['withdrawal']['total_amount'] ?? 0,
                ],
                'commission' => [
                    'count' => $transactionSummary['commission']['count'] ?? 0,
                    'total_amount' => $transactionSummary['commission']['total_amount'] ?? 0,
                ],
                'expenses' => [
                    'count' => $transactionSummary['expenses']['count'] ?? 0,
                    'total_amount' => $transactionSummary['expenses']['total_amount'] ?? 0,
                ],
                'transfer' => [
                    'count' => $transactionSummary['transfer']['count'] ?? 0,
                    'total_amount' => $transactionSummary['transfer']['total_amount'] ?? 0,
                ],
            ],
        ];

        return responseData(true, Response::HTTP_OK,
            'Dashboard data retrieved successfully', $data);
    }

    public function getDashboardDataByUserId(DashboardRequest $request, $id): ResponseData
    {
        if(!$id and Auth::user()->model !== SuperAdmin::class ){
            $id = Auth::user()->id;
        }
        $totalUsers = $this->dashboardRepository->getTotalUsersByUserId($id);
        $balance = $this->dashboardRepository->getTotalBalance($id);
        $filters = [];
        $transactionSummary = $this->dashboardRepository->getTransactionSummaryByTypeAndUserId($filters, $id);

        $data = [
            'total_users' => $totalUsers,
            'balance' => $balance,
            'transaction_summary' => [
                'deposit' => [
                    'count' => $transactionSummary['deposit']['count'] ?? 0,
                    'total_amount' => $transactionSummary['deposit']['total_amount'] ?? 0,
                ],
                'withdrawals' => [
                    'count' => $transactionSummary['withdrawal']['count'] ?? 0,
                    'total_amount' => $transactionSummary['withdrawal']['total_amount'] ?? 0,
                ],
                'commission' => [
                    'count' => $transactionSummary['commission']['count'] ?? 0,
                    'total_amount' => $transactionSummary['commission']['total_amount'] ?? 0,
                ],
                'expenses' => [
                    'count' => $transactionSummary['expenses']['count'] ?? 0,
                    'total_amount' => $transactionSummary['expenses']['total_amount'] ?? 0,
                ],
                'transfer' => [
                    'count' => $transactionSummary['transfer']['count'] ?? 0,
                    'total_amount' => $transactionSummary['transfer']['total_amount'] ?? 0,
                ],
            ],
        ];

        return responseData(true, Response::HTTP_OK,
            'Dashboard data retrieved successfully', $data);
    }

    public function getDashboardDataByCustomerId(DashboardRequest $request, $id): ResponseData
    {
        if(!$id and Auth::user()->model !== SuperAdmin::class ){
            $id = Auth::user()->branch_id;
        }
        $totalUsers = $this->dashboardRepository->getTotalUsers($id);
        $balance = $this->dashboardRepository->getTotalBalance($id);
        $filters = [];
        $transactionSummary = $this->dashboardRepository->getTransactionSummaryByType($filters, $id);

        $data = [
            'total_users' => $totalUsers,
            'balance' => $balance,
            'transaction_summary' => [
                'deposit' => [
                    'count' => $transactionSummary['deposit']['count'] ?? 0,
                    'total_amount' => $transactionSummary['deposit']['total_amount'] ?? 0,
                ],
                'withdrawals' => [
                    'count' => $transactionSummary['withdrawal']['count'] ?? 0,
                    'total_amount' => $transactionSummary['withdrawal']['total_amount'] ?? 0,
                ],
                'commission' => [
                    'count' => $transactionSummary['commission']['count'] ?? 0,
                    'total_amount' => $transactionSummary['commission']['total_amount'] ?? 0,
                ],
                'expenses' => [
                    'count' => $transactionSummary['expenses']['count'] ?? 0,
                    'total_amount' => $transactionSummary['expenses']['total_amount'] ?? 0,
                ],
                'transfer' => [
                    'count' => $transactionSummary['transfer']['count'] ?? 0,
                    'total_amount' => $transactionSummary['transfer']['total_amount'] ?? 0,
                ],
            ],
        ];

        return responseData(true, Response::HTTP_OK,
            'Dashboard data retrieved successfully', $data);
    }
}
