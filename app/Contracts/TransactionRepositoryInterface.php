<?php

namespace App\Contracts;

use App\Enums\TransactionType;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Pagination\LengthAwarePaginator;

interface TransactionRepositoryInterface
{

    /**
     * Fetch all \App\Models\Transaction records.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll(): EloquentCollection;

    /**
     * Fetch \App\Models\Transaction record by ID.
     *
     * @param int $id
     * @return \App\Models\Transaction|null
     */
    public function getById(int $id): null|Transaction;

    /**
     * Fetch \App\Models\Transaction record by ID.
     *
     * @param int $id
     * @return \App\Models\Transaction|null
     */
    public function getByBranchIdAndId(int $branchId, int $id): null|Transaction;

    /**
     * Fetch \App\Models\Transaction record by ID.
     *
     * @param string $reference
     * @return \App\Models\Transaction|null
     */
    public function getByReference(string $reference): null|Transaction;

    /**
     * Delete \App\Models\Transaction record by ID.
     *
     * @param int $id
     * @return void
     */
    public function delete(int $id): void;

    /**
     * Create \App\Models\Transaction record.
     *
     * @param array $arrayDetails
     * @return \App\Models\Transaction
     */
    public function create(array $arrayDetails): Transaction;

    /**
     * Fetch or create a single \App\Models\Transaction record.
     *
     * @param array $matchDetails
     * @param array $arrayDetails
     * @return \App\Models\Transaction
     */
    public function firstOrCreate(array $matchDetails, array $arrayDetails): Transaction;

    /**
     * Update \App\Models\Transaction record.
     *
     * @param int $id
     * @param array $arrayDetails
     * @return int
     */
    public function update(int $id, array $arrayDetails): int;

    /**
     * Update \App\Models\Transaction record.
     *
     * @param int $pageSize
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getPaginated(int $pageSize): LengthAwarePaginator;

    /**
     * Fetch \App\Models\Transaction record by transaction type.
     *
     * @param TransactionType $transactionType
     * @param int $pageSize
     * @return LengthAwarePaginator
     */
    public function getByTransactionTypePaginated(TransactionType $transactionType, int $pageSize): LengthAwarePaginator;

    /**
     * Fetch \App\Models\Transaction record by transaction type.
     *
     * @param TransactionType $transactionType
     * @return EloquentCollection
     */
    public function getByTransactionType(TransactionType $transactionType): EloquentCollection;

    /**
     * Fetch \App\Models\Transaction record by ID.
     *
     * @param TransactionType $transactionType
     * @param int $id
     * @return Transaction|null
     */
    public function getByTransactionTypeAndId(TransactionType $transactionType, int $id): null|Transaction;

    /**
     * Fetch \App\Models\Transaction record by transaction type.
     *
     * @param TransactionType $transactionType
     * @param int $branchId
     * @param int $pageSize
     * @return LengthAwarePaginator
     */
    public function getByTransactionTypeAndBranchIdPaginated(TransactionType $transactionType, int $branchId, int $pageSize): LengthAwarePaginator;


    /**
     * Fetch \App\Models\Transaction record by transaction type.
     *
     * @param TransactionType $transactionType
     * @param int $branchId
     * @return EloquentCollection
     */
    public function getByTransactionTypeAndBranchId(TransactionType $transactionType, int $branchId,): EloquentCollection;

    /**
     * Update \App\Models\Transaction record.
     *
     * @param int $branchId
     * @param int $pageSize
     * @return LengthAwarePaginator
     */
    public function getByBranchIdPaginated(int $branchId, int $pageSize): LengthAwarePaginator;

    /**
     * Fetch all \App\Models\Transaction records.
     *
     * @param int $branchId
     * @return EloquentCollection
     */
    public function getByBranchId(int $branchId): EloquentCollection;

    /**
     * Search all \App\Models\Transaction records.
     *
     * @param string $value
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function search(string $value): EloquentCollection;

}
