<?php

namespace App\Repositories;

use App\Contracts\TransactionRepositoryInterface;
use App\Enums\TransactionType;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Pagination\LengthAwarePaginator;

class TransactionRepository implements TransactionRepositoryInterface
{
    /**
     * Base query that excludes reversed transactions unless we're specifically looking for reversals
     */
    protected function baseQuery(?TransactionType $transactionType = null)
    {
        $query = Transaction::query();
        
        // Only exclude reversed transactions if we're not specifically querying for reversals
        if (!$transactionType || $transactionType->value !== TransactionType::REVERSAL->value) {
            $query->whereNull('reverses_id')
                  ->whereNull('reversed_by');
        }
        
        return $query;
    }

    /**
     * Fetch all \App\Models\Transaction records.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll(): EloquentCollection
    {
        return $this->baseQuery()->get();
    }

    /**
     * Fetch \App\Models\Transaction record by ID.
     *
     * @param int $id
     * @return Transaction|null
     */
    public function getById(int $id): null|Transaction
    {
        return Transaction::find($id);
    }

    /**
     * Fetch \App\Models\Transaction record by ID.
     *
     * @param int $branchId
     * @param int $id
     * @return Transaction|null
     */
    public function getByBranchIdAndId(int $branchId, int $id): null|Transaction
    {
        return Transaction::where('branch_id', $branchId)
            ->where('id', $id)
            ->first();
    }

    /**
     * Fetch \App\Models\Transaction record by reference.
     *
     * @param string $reference
     * @return Transaction|null
     */
    public function getByReference(string $reference): null|Transaction
    {
        return Transaction::where('reference', $reference)
            ->first();
    }

    /**
     * Delete \App\Models\Transaction record by ID.
     *
     * @param int $id
     * @return void
     */
    public function delete(int $id): void
    {
        Transaction::destroy($id);
    }

    /**
     * Create \App\Models\Transaction record.
     *
     * @param array $arrayDetails
     * @return Transaction
     */
    public function create(array $arrayDetails): Transaction
    {
        return Transaction::create($arrayDetails);
    }

    /**
     * Fetch or create a single \App\Models\Transaction record.
     *
     * @param array $matchDetails
     * @param array $arrayDetails
     * @return Transaction
     */
    public function firstOrCreate(array $matchDetails, array $arrayDetails): Transaction
    {
        return Transaction::firstOrCreate($matchDetails, $arrayDetails);
    }

    /**
     * Update \App\Models\Transaction record.
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
     * Update \App\Models\Transaction record.
     *
     * @param int $pageSize
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getPaginated(int $pageSize): LengthAwarePaginator
    {
        return $this->baseQuery()
            ->paginate($pageSize);
    }

    /**
     * Fetch \App\Models\Transaction record by transaction type.
     *
     * @param TransactionType $transactionType
     * @param int $pageSize
     * @return LengthAwarePaginator
     */
    public function getByTransactionTypePaginated(TransactionType $transactionType, int $pageSize): LengthAwarePaginator
    {
        return $this->baseQuery($transactionType)
            ->where('transaction_type', $transactionType)
            ->paginate($pageSize);
    }

    /**
     * Fetch \App\Models\Transaction record by transaction type.
     *
     * @param TransactionType $transactionType
     * @return EloquentCollection
     */
    public function getByTransactionType(TransactionType $transactionType): EloquentCollection
    {
        return $this->baseQuery($transactionType)
            ->where('transaction_type', $transactionType)
            ->get();
    }

    /**
     * Fetch \App\Models\Transaction record by ID.
     *
     * @param TransactionType $transactionType
     * @param int $id
     * @return Transaction|null
     */
    public function getByTransactionTypeAndId(TransactionType $transactionType, int $id): null|Transaction
    {
        $query = $this->baseQuery($transactionType);

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
     * Fetch \App\Models\Transaction record by transaction type.
     *
     * @param TransactionType $transactionType
     * @param int $branchId
     * @param int $pageSize
     * @return LengthAwarePaginator
     */
    public function getByTransactionTypeAndBranchIdPaginated(TransactionType $transactionType, int $branchId, int $pageSize): LengthAwarePaginator
    {
        $query = $this->baseQuery($transactionType)
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
     * Fetch \App\Models\Transaction record by transaction type and branch id.
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

        return $query->where('transaction_type', $transactionType)
            ->get();
    }

    /**
     * Update \App\Models\Transaction record.
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
     * Fetch all \App\Models\Transaction records.
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
     * Search all \App\Models\Transaction records.
     *
     * @param string $value
     * @return \Illuminate\Database\Eloquent\Collection
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
     * Fetch \App\Models\Transaction record by transaction type.
     *
     * @param TransactionType $transactionType
     * @param int $userId
     * @param int $pageSize
     * @return LengthAwarePaginator
     */
    public function getByTransactionTypeAndUserIdPaginated(TransactionType $transactionType, int $userId, int $pageSize): LengthAwarePaginator
    {
        $startDate = request('startDate');
        $endDate = request('endDate');
        
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
     * Fetch \App\Models\Transaction record by transaction type and user id.
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
     * Get only reversed transactions
     *
     * @return EloquentCollection
     */
    public function getReversedTransactions(): EloquentCollection
    {
        return Transaction::whereNotNull('reverses_id')
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
        return Transaction::with(['reversedTransaction', 'reversalTransaction'])
            ->paginate($pageSize);
    }
}