<?php

namespace App\Repositories;

use App\Contracts\CustomerTransactionRepositoryInterface;
use App\Enums\TransactionType;
use App\Models\CustomerTransaction;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Pagination\LengthAwarePaginator;

class CustomerTransactionRepository implements CustomerTransactionRepositoryInterface
{

    /**
     * Fetch all \App\Models\CustomerTransaction records.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll(): EloquentCollection
    {
        return CustomerTransaction::all();
    }

    /**
     * Fetch \App\Models\CustomerTransaction record by ID.
     *
     * @param int $id
     * @return \App\Models\CustomerTransaction|null
     */
    public function getById(int $id): null|CustomerTransaction
    {
        return CustomerTransaction::find($id);
    }

    /**
     * Fetch \App\Models\CustomerTransaction record by ID.
     *
     * @param string $reference
     * @return \App\Models\CustomerTransaction|null
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
     * @return \App\Models\CustomerTransaction
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
     * @return \App\Models\CustomerTransaction
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
        return CustomerTransaction::where('id', $id)->update($arrayDetails);
    }

    /**
     * Update \App\Models\CustomerTransaction record.
     *
     * @param int $pageSize
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getPaginated(int $pageSize): LengthAwarePaginator
    {
        return CustomerTransaction::paginate($pageSize);
    }

    public function getByTransactionTypePaginated(TransactionType $transactionType, int $pageSize): LengthAwarePaginator
    {
        return CustomerTransaction::where('transaction_type', $transactionType)->paginate($pageSize);
    }

    /**
     * Fetch \App\Models\CustomerTransaction record by transaction type.
     *
     * @param TransactionType $transactionType
     * @return EloquentCollection
     */
    public function getByTransactionType(TransactionType $transactionType): EloquentCollection
    {
        return CustomerTransaction::where('transaction_type', $transactionType)->get();
    }

    /**
     * Fetch \App\Models\CustomerTransaction record by ID.
     *
     * @param TransactionType $transactionType
     * @param int $id
     * @return CustomerTransaction|null
     */
    public function getByTransactionTypeAndId(TransactionType $transactionType, int $id): null|CustomerTransaction
    {
        return CustomerTransaction::where('transaction_type', $transactionType)->where('id',$id)->first();
    }


    /**
     * Update \App\Models\CustomerTransaction record.
     *
     * @param int $branchId
     * @param int $pageSize
     * @return LengthAwarePaginator
     */
    public function getByBranchIdPaginated(int $branchId, int $pageSize): LengthAwarePaginator
    {
        return CustomerTransaction::where('branch_id', $branchId)->paginate(pageSize($pageSize));
    }

    /**
     * Fetch all \App\Models\CustomerTransaction records.
     *
     * @param int $branchId
     * @return EloquentCollection
     */
    public function getByBranchId(int $branchId): EloquentCollection
    {
        return CustomerTransaction::where('branch_id', $branchId)->get();
    }

    /**
     * Fetch \App\Models\CustomerTransaction record by ID.
     *
     * @param int $branchId
     * @param int $id
     * @return CustomerTransaction|null
     */
    public function getByBranchIdAndId(int $branchId, int $id): null|CustomerTransaction
    {
        return CustomerTransaction::where('branch_id', $branchId)->where('id', $id)->first();
    }
}
