<?php

namespace App\Repositories;

use App\Contracts\CustomerRepositoryInterface;
use App\Enums\TransactionType;
use App\Models\Customer;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Pagination\LengthAwarePaginator;

class CustomerRepository implements CustomerRepositoryInterface
{

    /**
     * Fetch all \App\Models\Customer records.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll(): EloquentCollection
    {
        return Customer::all();
    }

    /**
     * Fetch \App\Models\Customer record by ID.
     *
     * @param int $id
     * @return \App\Models\Customer|null
     */
    public function getById(int $id): null|Customer
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return null;
        }
    
        // ✅ Calculate deposit and withdrawal totals separately
        $total_deposit = Transaction::where('customer_id', $id)
            ->where('transaction_type', TransactionType::DEPOSIT->value)
            ->sum('amount') ?? 0;

        $total_withdrawal = Transaction::where('customer_id', $id)
            ->where('transaction_type', TransactionType::WITHDRAWAL->value)
            ->sum('amount') ?? 0;

        // ✅ Attach computed values to the customer object
        $customer->total_deposit = $total_deposit;
        $customer->total_withdrawal = $total_withdrawal;

        return $customer; // ✅ No breaking changes

    }

    /**
     * Fetch \App\Models\Customer record by ID.
     *
     * @param string $accountId
     * @return Customer|null
     */
    public function getByAccountId(string $accountId): null|Customer
    {
        return Customer::where('account_id', $accountId)->first();
    }

    /**
     * Delete \App\Models\Customer record by ID.
     *
     * @param int $id
     * @return void
     */
    public function delete(int $id): void
    {
        Customer::destroy($id);
    }

    /**
     * Create \App\Models\Customer record.
     *
     * @param array $arrayDetails
     * @return \App\Models\Customer
     */
    public function create(array $arrayDetails): Customer
    {
        return Customer::create($arrayDetails);
    }

    /**
     * Fetch or create a single \App\Models\Customer record.
     *
     * @param array $matchDetails
     * @param array $arrayDetails
     * @return \App\Models\Customer
     */
    public function firstOrCreate(array $matchDetails, array $arrayDetails): Customer
    {
        return Customer::firstOrCreate($matchDetails, $arrayDetails);
    }

    /**
     * Update \App\Models\Customer record.
     *
     * @param int $id
     * @param array $arrayDetails
     * @return int
     */
    public function update(int $id, array $arrayDetails): int
    {
        return Customer::where('id', $id)->update($arrayDetails);
    }

    /**
     * Update \App\Models\Customer record.
     *
     * @param int $pageSize
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getPaginated(int $pageSize): LengthAwarePaginator
    {
        return Customer::paginate(pageSize($pageSize));
    }

    /**
     * Update \App\Models\Customer record.
     *
     * @param int $branchId
     * @param int $pageSize
     * @return LengthAwarePaginator
     */
    public function getByBranchIdPaginated(int $branchId, int $pageSize): LengthAwarePaginator
    {
        return Customer::where('branch_id', $branchId)->paginate(pageSize($pageSize));
    }

    /**
     * Fetch all \App\Models\Customer records.
     *
     * @param int $branchId
     * @return EloquentCollection
     */
    public function getByBranchId(int $branchId): EloquentCollection
    {
        return Customer::where('branch_id', $branchId)->get();
    }

    /**
     * Fetch \App\Models\Customer record by ID.
     *
     * @param int $branchId
     * @param int $id
     * @return Customer|null
     */
    public function getByBranchIdAndId(int $branchId, int $id): null|Customer
    {
        return Customer::where('branch_id', $branchId)->where('id', $id)->first();
    }

    /**
     * Update \App\Models\Customer record.
     *
     * @param int $userId
     * @param int $pageSize
     * @return LengthAwarePaginator
     */
    public function getByUserIdPaginated(int $userId, int $pageSize): LengthAwarePaginator
    {
        return Customer::where('user_id', $userId)->paginate(pageSize($pageSize));
    }

    /**
     * Fetch all \App\Models\Customer records.
     *
     * @param int $userId
     * @return EloquentCollection
     */
    public function getByUserId(int $userId): EloquentCollection
    {
        return Customer::where('user_id', $userId)->get();
    }

    /**
     * Fetch \App\Models\Customer record by ID.
     *
     * @param int $userId
     * @param int $id
     * @return Customer|null
     */
    public function getByUserIdAndId(int $userId, int $id): null|Customer
    {
        return Customer::where('user_id', $userId)->where('id',$id)->first();
    }

    /**
     * Search all \App\Models\Customer records.
     *
     * @param string $value
     * @param string|null $branchId
     * @param string|null $userId
     * @param int $perPage
     * @return EloquentCollection
     */
    public function search(string $value, ?string $branchId = null, ?string $userId = null, int $perPage = 10): EloquentCollection
    {
        return Customer::query()
            ->where(function ($query) use ($value) {
                $query->where('first_name', 'LIKE', "%{$value}%")
                    ->orWhere('surname', 'LIKE', "%{$value}%")
                    ->orWhere('middle_name', 'LIKE', "%{$value}%")
                    ->orWhere('account_id', 'LIKE', "%{$value}%");
            })
            ->when($branchId, fn($query) => $query->where('branch_id', $branchId))
            ->when($userId, fn($query) => $query->where('user_id', $userId))
            ->get();

    }

    /**
     * Search all \App\Models\Customer records by user id.
     *
     * @param string $value
     * @param string|int $userId
     * @return EloquentCollection
     */
    public function searchByUserId(string $value, string|int $userId): EloquentCollection
    {
        return Customer::search($value)->where('user_id', $userId)->get();
    }

    /**
     * Search all \App\Models\Customer records by branch id.
     *
     * @param string $value
     * @param string|int $branchId
     * @return EloquentCollection
     */
    public function searchByBranchId(string $value, string|int $branchId): EloquentCollection
    {
        return Customer::search($value)->where('branch_id', $branchId)->get();

    }

    /**
     * Search all \App\Models\Customer records by branch id and user id.
     *
     * @param string $value
     * @param string|int $branchId
     * @param string|int $userId
     * @return EloquentCollection
     */
    public function searchByBranchIdAndUserId(string $value, string|int $branchId, string|int $userId): EloquentCollection
    {
        return Customer::search($value)->where('branch_id', $branchId)->where('user_id', $userId)->get();
    }
}
