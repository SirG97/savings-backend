<?php

namespace App\Repositories;

use App\Contracts\DashboardRepositoryInterface;
use App\Models\Customer;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Pagination\LengthAwarePaginator;

class DashboardRepository implements DashboardRepositoryInterface
{

    /**
     * Fetch all \App\Models\Customer records.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTotalUsers(int $id = null): int
    {
        if ($id !== null) {
            return Customer::where('branch_id', $id)->count();
        }
        return Customer::count();
    }
    public function getTotalBalance(int $id = null): int
    {
        if ($id !== null) {
            return Wallet::where('id', $id)->sum('balance');
        }
        return Wallet::sum('balance');
    }

    public function getTransactionSummaryByType(array $filters = [], int $id = null): array
    {
        $query = Transaction::query();

        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $query->whereBetween('created_at', [$filters['start_date'], $filters['end_date']]);
        }
        if($id !== null){
            $query->where('branch_id', $id);
        }

        return $query->selectRaw('transaction_type, COUNT(*) as count, SUM(amount) as total_amount')
            ->groupBy('transaction_type')
            ->get()
            ->keyBy('transaction_type')
            ->toArray();
    }

    public function getTotalUsersByUserId(int $id = null): int
    {
        if ($id !== null) {
            return Customer::where('user_id', $id)->count();
        }
        return Customer::count();
    }

    public function getTransactionSummaryByTypeAndUserId(array $filters = [], int $id = null): array
    {
        $query = Transaction::query();

        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $query->whereBetween('created_at', [$filters['start_date'], $filters['end_date']]);
        }
        if($id !== null){
            $query->where('user_id', $id);
        }

        return $query->selectRaw('transaction_type, COUNT(*) as count, SUM(amount) as total_amount')
            ->groupBy('transaction_type')
            ->get()
            ->keyBy('transaction_type')
            ->toArray();
    }

    public function getTransactionSummaryByTypeAndCustomerId(array $filters = [], int $id = null): array
    {
        $query = Transaction::query();

        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $query->whereBetween('created_at', [$filters['start_date'], $filters['end_date']]);
        }
        if($id !== null){
            $query->where('customer_id', $id);
        }

        return $query->selectRaw('transaction_type, COUNT(*) as count, SUM(amount) as total_amount')
            ->groupBy('transaction_type')
            ->get()
            ->keyBy('transaction_type')
            ->toArray();
    }
}
