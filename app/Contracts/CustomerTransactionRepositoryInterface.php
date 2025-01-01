<?php

namespace App\Contracts;

use App\Enums\TransactionType;
use App\Models\CustomerTransaction;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Pagination\LengthAwarePaginator;

interface CustomerTransactionRepositoryInterface
{

    /**
     * Fetch all \App\Models\CustomerTransaction records.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll(): EloquentCollection;

    /**
     * Fetch \App\Models\CustomerTransaction record by ID.
     *
     * @param int $id
     * @return \App\Models\CustomerTransaction|null
     */
    public function getById(int $id): null|CustomerTransaction;

    /**
     * Fetch \App\Models\CustomerTransaction record by ID.
     *
     * @param string $reference
     * @return \App\Models\CustomerTransaction|null
     */
    public function getByReference(string $reference): null|CustomerTransaction;

    /**
     * Delete \App\Models\CustomerTransaction record by ID.
     *
     * @param int $id
     * @return void
     */
    public function delete(int $id): void;

    /**
     * Create \App\Models\CustomerTransaction record.
     *
     * @param array $arrayDetails
     * @return \App\Models\CustomerTransaction
     */
    public function create(array $arrayDetails): CustomerTransaction;

    /**
     * Fetch or create a single \App\Models\CustomerTransaction record.
     *
     * @param array $matchDetails
     * @param array $arrayDetails
     * @return \App\Models\CustomerTransaction
     */
    public function firstOrCreate(array $matchDetails, array $arrayDetails): CustomerTransaction;

    /**
     * Update \App\Models\CustomerTransaction record.
     *
     * @param int $id
     * @param array $arrayDetails
     * @return int
     */
    public function update(int $id, array $arrayDetails): int;

    /**
     * Update \App\Models\CustomerTransaction record.
     *
     * @param int $pageSize
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getPaginated(int $pageSize): LengthAwarePaginator;

    /**
     * Update \App\Models\CustomerTransaction record.
     *
     * @param TransactionType $transactionType
     * @param int $pageSize
     * @return LengthAwarePaginator
     */
    public function getByTransactionTypePaginated(TransactionType $transactionType, int $pageSize): LengthAwarePaginator;

    /**
     * Fetch \App\Models\CustomerTransaction record by transaction type.
     *
     * @param TransactionType $transactionType
     * @return EloquentCollection
     */
    public function getByTransactionType(TransactionType $transactionType): EloquentCollection;

    /**
     * Fetch \App\Models\CustomerTransaction record by ID.
     *
     * @param TransactionType $transactionType
     * @param int $id
     * @return CustomerTransaction|null
     */
    public function getByTransactionTypeAndId(TransactionType $transactionType, int $id): null|CustomerTransaction;
}
