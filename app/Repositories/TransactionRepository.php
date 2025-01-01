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
     * @return \App\Models\Transaction|null
     */
    public function getById(int $id): null|Transaction
    {
        return Transaction::find($id);
    }

    /**
     * Fetch \App\Models\Transaction record by ID.
     *
     * @param string $reference
     * @return \App\Models\Transaction|null
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
     * @return \App\Models\Transaction
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
     * @return \App\Models\Transaction
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
     * @param TransactionType $transactionType
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
        return Transaction::where('transaction_type', $transactionType)->where('id',$id)->first();
    }
}
