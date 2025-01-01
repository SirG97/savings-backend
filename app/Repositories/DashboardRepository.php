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
    public function getTotalUsers(): int
    {
        return Customer::count();
    }
    public function getTotalBalance(): int
    {
        return Wallet::sum('balance');
    }

    public function getTransactionSummaryByType(array $filters = []): array
    {
        $query = Transaction::query();

        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $query->whereBetween('created_at', [$filters['start_date'], $filters['end_date']]);
        }

        return $query->selectRaw('transaction_type, COUNT(*) as count, SUM(amount) as total_amount')
            ->groupBy('transaction_type')
            ->get()
            ->keyBy('transaction_type')
            ->toArray();
    }
}
