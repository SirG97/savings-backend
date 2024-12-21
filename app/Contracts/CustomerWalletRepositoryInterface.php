<?php

namespace App\Contracts;

use App\Models\CustomerWallet;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Pagination\LengthAwarePaginator;

interface CustomerWalletRepositoryInterface
{

    /**
     * Fetch all \App\Models\CustomerWallet records.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll(): EloquentCollection;

    /**
     * Fetch \App\Models\CustomerWallet record by ID.
     *
     * @param int $id
     * @return \App\Models\CustomerWallet|null
     */
    public function getById(int $id): null|CustomerWallet;

    /**
     * Delete \App\Models\CustomerWallet record by ID.
     *
     * @param int $id
     * @return void
     */
    public function delete(int $id): void;

    /**
     * Create \App\Models\CustomerWallet record.
     *
     * @param array $arrayDetails
     * @return \App\Models\CustomerWallet
     */
    public function create(array $arrayDetails): CustomerWallet;

    /**
     * Fetch or create a single \App\Models\CustomerWallet record.
     *
     * @param array $matchDetails
     * @param array $arrayDetails
     * @return \App\Models\CustomerWallet
     */
    public function firstOrCreate(array $matchDetails, array $arrayDetails): CustomerWallet;

    /**
     * Update \App\Models\CustomerWallet record.
     *
     * @param int $id
     * @param array $arrayDetails
     * @return int
     */
    public function update(int $id, array $arrayDetails): int;

    /**
     * Update \App\Models\CustomerWallet record.
     *
     * @param int $pageSize
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getPaginated(int $pageSize): LengthAwarePaginator;
}
