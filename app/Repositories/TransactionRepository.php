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
     * Fetch all \App\Models\Transaction records.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll(): EloquentCollection
    {
        return Transaction::all();
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
        return Transaction::where('branch_id',$branchId)->where('id',$id)->first();
    }

    /**
     * Fetch \App\Models\Transaction record by ID.
     *
     * @param string $reference
     * @return Transaction|null
     */
    public function getByReference(string $reference): null|Transaction
    {
        return Transaction::where('reference', $reference)->first();
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
        return Transaction::where('id', $id)->update($arrayDetails);
    }

    /**
     * Update \App\Models\Transaction record.
     *
     * @param int $pageSize
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getPaginated(int $pageSize): LengthAwarePaginator
    {
        return Transaction::paginate($pageSize);
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
        return Transaction::where('transaction_type', $transactionType)->paginate($pageSize);
    }

    /**
     * Fetch \App\Models\Transaction record by transaction type.
     *
     * @param string $transactionType
     * @return EloquentCollection
     */
    public function getByTransactionType(TransactionType $transactionType): EloquentCollection
    {
        return Transaction::where('transaction_type', $transactionType)->get();
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

        if ($transactionType->value == 'loan') {
            return Transaction::whereIn('transaction_type', [
                TransactionType::LOAN_CREDIT->value, 
                TransactionType::LOAN_DEBIT->value])->where('id',$id)->first();
        }
        return Transaction::where('transaction_type', $transactionType)->where('id',$id)->first();
    }

    /**
     * Fetch \App\Models\Transaction record by transaction type.
     *
     * @param TransactionType $transactionType
     * @param int $pageSize
     * @return LengthAwarePaginator
     */
    public function getByTransactionTypeAndBranchIdPaginated(TransactionType $transactionType, int $branchId, int $pageSize): LengthAwarePaginator
    {
        if ($transactionType->value == 'loan') {
            return Transaction::whereIn('transaction_type', [
                TransactionType::LOAN_CREDIT->value, 
                TransactionType::LOAN_DEBIT->value])
                ->where('branch_id',$branchId)->paginate($pageSize);

        }

        return Transaction::where('transaction_type', $transactionType)->where('branch_id',$branchId)->paginate($pageSize);
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
        if($transactionType->value == 'loan'){
            return Transaction::whereIn('transaction_type', [TransactionType::LOAN_CREDIT->value, TransactionType::LOAN_DEBIT->value])->where('branch_id', $branchId)->get();
        }

        return Transaction::where('transaction_type', $transactionType)->where('branch_id', $branchId)->get();
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
        return Transaction::where('branch_id', $branchId)->paginate(pageSize($pageSize));
    }

    /**
     * Fetch all \App\Models\Transaction records.
     *
     * @param int $branchId
     * @return EloquentCollection
     */
    public function getByBranchId(int $branchId): EloquentCollection
    {
        return Transaction::where('branch_id', $branchId)->get();
    }

    /**
     * Search all \App\Models\Transaction records.
     *
     * @param string $value
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function search(string $value): EloquentCollection
    {
        return Transaction::search($value)->get();
    }


  /**
     * Fetch \App\Models\Transaction record by transaction type.
     *
     * @param TransactionType $transactionType
     * @param int $pageSize
     * @return LengthAwarePaginator
     */
    public function getByTransactionTypeAndUserIdPaginated(TransactionType $transactionType, int $userId, int $pageSize): LengthAwarePaginator
    {
        $startDate = request('startDate');
        $endDate = request('endDate');
        
       
        if ($transactionType->value == 'loan') {
            return Transaction::whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('transaction_type', [
                TransactionType::LOAN_CREDIT->value, 
                TransactionType::LOAN_DEBIT->value])
                ->where('user_id',$userId)->paginate($pageSize);

        }

        return Transaction::whereBetween('created_at', [$startDate, $endDate])
        ->where('transaction_type', $transactionType)->where('user_id',$userId)->paginate($pageSize);
    }

    /**
     * Fetch \App\Models\Transaction record by transaction type and branch id.
     *
     * @param TransactionType $transactionType
     * @param int $userId
     * @return EloquentCollection
     */
    public function getByTransactionTypeAndUserId(TransactionType $transactionType, int $userId): EloquentCollection
    {
        if($transactionType->value == 'loan'){
            return Transaction::whereIn('transaction_type', [TransactionType::LOAN_CREDIT->value, TransactionType::LOAN_DEBIT->value])->where('user_id', $userId)->get();
        }

        return Transaction::where('transaction_type', $transactionType)->where('user_id', $userId)->get();
    }
}
