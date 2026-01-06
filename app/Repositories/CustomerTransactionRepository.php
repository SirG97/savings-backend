<?php

namespace App\Repositories;

use App\Contracts\CustomerTransactionRepositoryInterface;
use App\Enums\TransactionType;
use App\Models\CustomerTransaction;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Pagination\LengthAwarePaginator;

class CustomerTransactionRepository implements CustomerTransactionRepositoryInterface
{
    /**
     * Base query that excludes reversed transactions unless we're specifically looking for reversals
     */
    protected function baseQuery(?TransactionType $transactionType = null)
    {
        $query = CustomerTransaction::query();
        
        // Only exclude reversed transactions if we're not specifically querying for reversals
        if (!$transactionType || $transactionType->value !== TransactionType::REVERSAL->value) {
            $query->whereNull('reverses_id')
                  ->whereNull('reversed_by');
        }
        
        return $query;
    }

    /**
     * Fetch all \App\Models\CustomerTransaction records.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll(): EloquentCollection
    {
        return $this->baseQuery()->get();
    }

    /**
     * Fetch \App\Models\CustomerTransaction record by ID.
     * Does NOT exclude reversed transactions since we want full details
     *
     * @param int $id
     * @return CustomerTransaction|null
     */
    public function getById(int $id): null|CustomerTransaction
    {
        return CustomerTransaction::find($id);
    }

    /**
     * Fetch \App\Models\CustomerTransaction record by reference.
     * Does NOT exclude reversed transactions since references should be unique
     *
     * @param string $reference
     * @return CustomerTransaction|null
     */
    public function getByReference(string $reference): null|CustomerTransaction
    {
        return CustomerTransaction::where('reference', $reference)->first();
    }

    /**
     * Delete \App\Models\CustomerTransaction record by ID.
     *
     * @param int $id
     * @return void
     */
    public function delete(int $id): void
    {
        CustomerTransaction::destroy($id);
    }

    /**
     * Create \App\Models\CustomerTransaction record.
     *
     * @param array $arrayDetails
     * @return CustomerTransaction
     */
    public function create(array $arrayDetails): CustomerTransaction
    {
        return CustomerTransaction::create($arrayDetails);
    }

    /**
     * Fetch or create a single \App\Models\CustomerTransaction record.
     *
     * @param array $matchDetails
     * @param array $arrayDetails
     * @return CustomerTransaction
     */
    public function firstOrCreate(array $matchDetails, array $arrayDetails): CustomerTransaction
    {
        return CustomerTransaction::firstOrCreate($matchDetails, $arrayDetails);
    }

    /**
     * Update \App\Models\CustomerTransaction record.
     *
     * @param int $id
     * @param array $arrayDetails
     * @return int
     */
    public function update(int $id, array $arrayDetails): int
    {
        return $this->baseQuery()
            ->where('id', $id)
            ->update($arrayDetails);
    }

    /**
     * Get paginated \App\Models\CustomerTransaction records.
     *
     * @param int $pageSize
     * @return LengthAwarePaginator
     */
    public function getPaginated(int $pageSize): LengthAwarePaginator
    {
        return $this->baseQuery()
            ->paginate($pageSize);
    }

    /**
     * Get paginated transactions by type.
     *
     * @param TransactionType $transactionType
     * @param int $pageSize
     * @return LengthAwarePaginator
     */
    public function getByTransactionTypePaginated(TransactionType $transactionType, int $pageSize): LengthAwarePaginator
    {
        $query = $this->baseQuery($transactionType);

        if ($transactionType->value == 'loan') {
            return $query->whereIn('transaction_type', [
                TransactionType::LOAN_CREDIT->value, 
                TransactionType::LOAN_DEBIT->value
            ])
            ->paginate($pageSize);
        }

        return $query->where('transaction_type', $transactionType)
            ->paginate($pageSize);
    }

    /**
     * Get transactions by type.
     *
     * @param TransactionType $transactionType
     * @return EloquentCollection
     */
    public function getByTransactionType(TransactionType $transactionType): EloquentCollection
    {
        $query = $this->baseQuery($transactionType);

        if ($transactionType->value == 'loan') {
            return $query->whereIn('transaction_type', [
                TransactionType::LOAN_CREDIT->value, 
                TransactionType::LOAN_DEBIT->value
            ])
            ->get();
        }

        return $query->where('transaction_type', $transactionType)
            ->get();
    }

    /**
     * Get transaction by type and ID.
     *
     * @param TransactionType $transactionType
     * @param int $id
     * @return CustomerTransaction|null
     */
    public function getByTransactionTypeAndId(TransactionType $transactionType, int $id): null|CustomerTransaction
    {
        $query = CustomerTransaction::query();

        if ($transactionType->value == 'loan') {
            return $query->whereIn('transaction_type', [
                TransactionType::LOAN_CREDIT->value, 
                TransactionType::LOAN_DEBIT->value
            ])
            ->where('id', $id)
            ->first();
        }

        return $query->where('transaction_type', $transactionType)
            ->where('id', $id)
            ->first();
    }

    /**
     * Get paginated transactions by branch ID.
     *
     * @param int $branchId
     * @param int $pageSize
     * @return LengthAwarePaginator
     */
    public function getByBranchIdPaginated(int $branchId, int $pageSize): LengthAwarePaginator
    {
        return $this->baseQuery()
            ->where('branch_id', $branchId)
            ->paginate($pageSize);
    }

    /**
     * Get transactions by branch ID.
     *
     * @param int $branchId
     * @return EloquentCollection
     */
    public function getByBranchId(int $branchId): EloquentCollection
    {
        return $this->baseQuery()
            ->where('branch_id', $branchId)
            ->get();
    }

    /**
     * Get transaction by branch ID and transaction ID.
     *
     * @param int $branchId
     * @param int $id
     * @return CustomerTransaction|null
     */
    public function getByBranchIdAndId(int $branchId, int $id): null|CustomerTransaction
    {
        return CustomerTransaction::where('branch_id', $branchId)
            ->where('id', $id)
            ->first();
    }

    /**
     * Search transactions.
     *
     * @param string $value
     * @return EloquentCollection
     */
    public function search(string $value): EloquentCollection
    {
        return $this->baseQuery()
            ->where(function($query) use ($value) {
                $query->where('reference', 'like', "%$value%")
                      ->orWhere('amount', 'like', "%$value%")
                      ->orWhere('description', 'like', "%$value%");
            })
            ->get();
    }

    /**
     * Get paginated transactions by type and customer ID.
     *
     * @param TransactionType $transactionType
     * @param int $customerId
     * @param int $pageSize
     * @return LengthAwarePaginator
     */
    public function getByTransactionTypeAndCustomerIdPaginated(TransactionType $transactionType, int $customerId, int $pageSize): LengthAwarePaginator
    {
        $startDate = Carbon::parse(request('startDate'));
        $endDate = Carbon::parse(request('endDate'));
        
        $query = $this->baseQuery($transactionType)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('customer_id', $customerId);

        if ($transactionType->value == 'loan') {
            return $query->whereIn('transaction_type', [
                TransactionType::LOAN_CREDIT->value, 
                TransactionType::LOAN_DEBIT->value
            ])
            ->paginate($pageSize);
        }

        return $query->where('transaction_type', $transactionType)
            ->paginate($pageSize);
    }

    /**
     * Get transactions by type and customer ID.
     *
     * @param TransactionType $transactionType
     * @param int $customerId
     * @return EloquentCollection
     */
    public function getByTransactionTypeAndCustomerId(TransactionType $transactionType, int $customerId): EloquentCollection
    {
        $query = $this->baseQuery($transactionType)
            ->where('customer_id', $customerId);

        if ($transactionType->value == 'loan') {
            return $query->whereIn('transaction_type', [
                TransactionType::LOAN_CREDIT->value, 
                TransactionType::LOAN_DEBIT->value
            ])
            ->get();
        }

        return $query->where('transaction_type', $transactionType)
            ->get();
    }

    /**
     * Get paginated transactions by type and user ID.
     *
     * @param TransactionType $transactionType
     * @param int $userId
     * @param int $pageSize
     * @return LengthAwarePaginator
     */
    public function getByTransactionTypeAndUserIdPaginated(TransactionType $transactionType, int $userId, int $pageSize): LengthAwarePaginator
    {
        $startDate = Carbon::parse(request('startDate'));
        $endDate = Carbon::parse(request('endDate'));
        
        $query = $this->baseQuery($transactionType)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('user_id', $userId);

        if ($transactionType->value == 'loan') {
            return $query->whereIn('transaction_type', [
                TransactionType::LOAN_CREDIT->value, 
                TransactionType::LOAN_DEBIT->value
            ])
            ->paginate($pageSize);
        }

        return $query->where('transaction_type', $transactionType)
            ->paginate($pageSize);
    }

    /**
     * Get transactions by type and user ID.
     *
     * @param TransactionType $transactionType
     * @param int $userId
     * @return EloquentCollection
     */
    public function getByTransactionTypeAndUserId(TransactionType $transactionType, int $userId): EloquentCollection
    {
        $query = $this->baseQuery($transactionType)
            ->where('user_id', $userId);

        if ($transactionType->value == 'loan') {
            return $query->whereIn('transaction_type', [
                TransactionType::LOAN_CREDIT->value, 
                TransactionType::LOAN_DEBIT->value
            ])
            ->get();
        }

        return $query->where('transaction_type', $transactionType)
            ->get();
    }

    /**
     * Get paginated transactions by type and branch ID.
     *
     * @param TransactionType $transactionType
     * @param int $branchId
     * @param int $pageSize
     * @return LengthAwarePaginator
     */
    public function getByTransactionTypeAndBranchIdPaginated(TransactionType $transactionType, int $branchId, int $pageSize): LengthAwarePaginator
    {
        $startDate = Carbon::parse(request('startDate'));
        $endDate = Carbon::parse(request('endDate'));
        
        $query = $this->baseQuery($transactionType)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('branch_id', $branchId);

        if ($transactionType->value == 'loan') {
            return $query->whereIn('transaction_type', [
                TransactionType::LOAN_CREDIT->value, 
                TransactionType::LOAN_DEBIT->value
            ])
            ->paginate($pageSize);
        }

        return $query->where('transaction_type', $transactionType)
            ->paginate($pageSize);
    }

    /**
     * Get transactions by type and branch ID.
     *
     * @param TransactionType $transactionType
     * @param int $branchId
     * @return EloquentCollection
     */
    public function getByTransactionTypeAndBranchId(TransactionType $transactionType, int $branchId): EloquentCollection
    {
        $query = $this->baseQuery($transactionType)
            ->where('branch_id', $branchId);

        if ($transactionType->value == 'loan') {
            return $query->whereIn('transaction_type', [
                TransactionType::LOAN_CREDIT->value, 
                TransactionType::LOAN_DEBIT->value
            ])
            ->get();
        }

        return $query->where('transaction_type', $transactionType->value)
            ->get();
    }

    /**
     * Get only reversed transactions
     *
     * @return EloquentCollection
     */
    public function getReversedTransactions(): EloquentCollection
    {
        return CustomerTransaction::whereNotNull('reverses_id')
            ->orWhereNotNull('reversed_by')
            ->get();
    }

    /**
     * Get transactions with their reversals
     *
     * @param int $pageSize
     * @return LengthAwarePaginator
     */
    public function getWithReversalsPaginated(int $pageSize): LengthAwarePaginator
    {
        return CustomerTransaction::with(['reversedTransaction', 'reversalTransaction'])
            ->paginate($pageSize);
    }
}