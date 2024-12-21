<?php

namespace App\Repositories;

use App\Contracts\CustomerWalletRepositoryInterface;
use App\Models\CustomerWallet;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Pagination\LengthAwarePaginator;

class CustomerWalletRepository implements CustomerWalletRepositoryInterface
{

    /**
     * Fetch all \App\Models\CustomerWallet records.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll(): EloquentCollection
    {
        return CustomerWallet::all();
    }

    /**
     * Fetch \App\Models\CustomerWallet record by ID.
     *
     * @param int $id
     * @return \App\Models\CustomerWallet|null
     */
    public function getById(int $id): null|CustomerWallet
    {
        return CustomerWallet::find($id);
    }

    /**
     * Delete \App\Models\CustomerWallet record by ID.
     *
     * @param int $id
     * @return void
     */
    public function delete(int $id): void
    {
        CustomerWallet::destroy($id);
    }

    /**
     * Create \App\Models\CustomerWallet record.
     *
     * @param array $arrayDetails
     * @return \App\Models\CustomerWallet
     */
    public function create(array $arrayDetails): CustomerWallet
    {
        return CustomerWallet::create($arrayDetails);
    }

    /**
     * Fetch or create a single \App\Models\CustomerWallet record.
     *
     * @param array $matchDetails
     * @param array $arrayDetails
     * @return \App\Models\CustomerWallet
     */
    public function firstOrCreate(array $matchDetails, array $arrayDetails): CustomerWallet
    {
        return CustomerWallet::firstOrCreate($matchDetails, $arrayDetails);
    }

    /**
     * Update \App\Models\CustomerWallet record.
     *
     * @param int $id
     * @param array $arrayDetails
     * @return int
     */
    public function update(int $id, array $arrayDetails): int
    {
        return CustomerWallet::where('id', $id)->update($arrayDetails);
    }

    /**
     * Update \App\Models\CustomerWallet record.
     *
     * @param int $pageSize
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getPaginated(int $pageSize): LengthAwarePaginator
    {
        return CustomerWallet::paginate($pageSize);
    }
}