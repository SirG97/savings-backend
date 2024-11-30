<?php

namespace App\Repositories;

use App\Contracts\WalletRepositoryInterface;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Pagination\LengthAwarePaginator;

class WalletRepository implements WalletRepositoryInterface
{

    /**
     * Fetch all \App\Models\Wallet records.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll(): EloquentCollection
    {
        return Wallet::all();
    }

    /**
     * Fetch \App\Models\Wallet record by ID.
     *
     * @param int $id
     * @return \App\Models\Wallet|null
     */
    public function getById(int $id): null|Wallet
    {
        return Wallet::find($id);
    }

    /**
     * Fetch \App\Models\Wallet record by branch ID.
     *
     * @param int $branchId
     * @return \App\Models\Wallet|null
     */
    public function getByBranchId(int $branchId): null|Wallet
    {
        return Wallet::where('branch_id', $branchId)->first();
    }

    /**
     * Delete \App\Models\Wallet record by ID.
     *
     * @param int $id
     * @return void
     */
    public function delete(int $id): void
    {
        Wallet::destroy($id);
    }

    /**
     * Create \App\Models\Wallet record.
     *
     * @param array $arrayDetails
     * @return \App\Models\Wallet
     */
    public function create(array $arrayDetails): Wallet
    {
        return Wallet::create($arrayDetails);
    }

    /**
     * Fetch or create a single \App\Models\Wallet record.
     *
     * @param array $matchDetails
     * @param array $arrayDetails
     * @return \App\Models\Wallet
     */
    public function firstOrCreate(array $matchDetails, array $arrayDetails): Wallet
    {
        return Wallet::firstOrCreate($matchDetails, $arrayDetails);
    }

    /**
     * Update \App\Models\Wallet record.
     *
     * @param int $id
     * @param array $arrayDetails
     * @return int
     */
    public function update(int $id, array $arrayDetails): int
    {
        return Wallet::where('id', $id)->update($arrayDetails);
    }

    /**
     * Update \App\Models\Wallet record.
     *
     * @param int $pageSize
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getPaginated(int $pageSize): LengthAwarePaginator
    {
        return Wallet::paginate($pageSize);
    }
}
