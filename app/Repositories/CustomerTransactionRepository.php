<?php

namespace App\Repositories;

use App\Contracts\CustomerTransactionRepositoryInterface;
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
}
