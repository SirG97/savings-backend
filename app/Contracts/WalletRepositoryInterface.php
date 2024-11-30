<?php

namespace App\Contracts;

use App\Models\Wallet;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Pagination\LengthAwarePaginator;

interface WalletRepositoryInterface
{

    /**
     * Fetch all \App\Models\Wallet records.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll(): EloquentCollection;

    /**
     * Fetch \App\Models\Wallet record by ID.
     *
     * @param int $id
     * @return \App\Models\Wallet|null
     */
    public function getById(int $id): null|Wallet;

    /**
     * Fetch \App\Models\Wallet record by branch ID.
     *
     * @param int $branchId
     * @return \App\Models\Wallet|null
     */
    public function getByBranchId(int $branchId): null|Wallet;

    /**
     * Delete \App\Models\Wallet record by ID.
     *
     * @param int $id
     * @return void
     */
    public function delete(int $id): void;

    /**
     * Create \App\Models\Wallet record.
     *
     * @param array $arrayDetails
     * @return \App\Models\Wallet
     */
    public function create(array $arrayDetails): Wallet;

    /**
     * Fetch or create a single \App\Models\Wallet record.
     *
     * @param array $matchDetails
     * @param array $arrayDetails
     * @return \App\Models\Wallet
     */
    public function firstOrCreate(array $matchDetails, array $arrayDetails): Wallet;

    /**
     * Update \App\Models\Wallet record.
     *
     * @param int $id
     * @param array $arrayDetails
     * @return int
     */
    public function update(int $id, array $arrayDetails): int;

    /**
     * Update \App\Models\Wallet record.
     *
     * @param int $pageSize
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getPaginated(int $pageSize): LengthAwarePaginator;
}
