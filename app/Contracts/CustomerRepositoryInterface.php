<?php

namespace App\Contracts;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Pagination\LengthAwarePaginator;

interface CustomerRepositoryInterface
{

    /**
     * Fetch all \App\Models\Customer records.
     *
     * @return EloquentCollection
     */
    public function getAll(): EloquentCollection;

    /**
     * Fetch \App\Models\Customer record by ID.
     *
     * @param int $id
     * @return Customer|null
     */
    public function getById(int $id): null|Customer;

    /**
     * Fetch \App\Models\Customer record by ID.
     *
     * @param string $accountId
     * @return Customer|null
     */
    public function getByAccountId(string $accountId): null|Customer;

    /**
     * Delete \App\Models\Customer record by ID.
     *
     * @param int $id
     * @return void
     */
    public function delete(int $id): void;

    /**
     * Create \App\Models\Customer record.
     *
     * @param array $arrayDetails
     * @return Customer
     */
    public function create(array $arrayDetails): Customer;

    /**
     * Fetch or create a single \App\Models\Customer record.
     *
     * @param array $matchDetails
     * @param array $arrayDetails
     * @return Customer
     */
    public function firstOrCreate(array $matchDetails, array $arrayDetails): Customer;

    /**
     * Update \App\Models\Customer record.
     *
     * @param int $id
     * @param array $arrayDetails
     * @return int
     */
    public function update(int $id, array $arrayDetails): int;

    /**
     * Update \App\Models\Customer record.
     *
     * @param int $pageSize
     * @return LengthAwarePaginator
     */
    public function getPaginated(int $pageSize): LengthAwarePaginator;

    /**
     * Update \App\Models\Customer record.
     *
     * @param int $branchId
     * @param int $pageSize
     * @return LengthAwarePaginator
     */
    public function getByBranchIdPaginated(int $branchId, int $pageSize): LengthAwarePaginator;

    /**
     * Fetch all \App\Models\Customer records.
     *
     * @param int $branchId
     * @return EloquentCollection
     */
    public function getByBranchId(int $branchId): EloquentCollection;

    /**
     * Fetch \App\Models\Customer record by ID.
     *
     * @param int $branchId
     * @param int $id
     * @return Customer|null
     */
    public function getByBranchIdAndId(int $branchId, int $id): null|Customer;

    /**
     * Update \App\Models\Customer record.
     *
     * @param int $userId
     * @param int $pageSize
     * @return LengthAwarePaginator
     */
    public function getByUserIdPaginated(int $userId, int $pageSize): LengthAwarePaginator;

    /**
     * Fetch all \App\Models\Customer records.
     *
     * @param int $userId
     * @return EloquentCollection
     */
    public function getByUserId(int $userId): EloquentCollection;

    /**
     * Fetch \App\Models\Customer record by ID.
     *
     * @param int $userId
     * @param int $id
     * @return Customer|null
     */
    public function getByUserIdAndId(int $userId, int $id): null|Customer;

    /**
     * Search all \App\Models\Customer records.
     *
     * @param string $value
     * @param string|null $branchId
     * @param string|null $userId
     * @param int $perPage
     * @return EloquentCollection
     */
    public function search(string $value, ?string $branchId, ?string $userId, int $perPage = 10): EloquentCollection;

    /**
     * Search all \App\Models\Customer records by user id.
     *
     * @param string $value
     * @param string|int $userId
     * @return EloquentCollection
     */
    public function searchByUserId(string $value, string|int $userId): EloquentCollection;

    /**
     * Search all \App\Models\Customer records by branch id.
     *
     * @param string $value
     * @param string|int $branchId
     * @return EloquentCollection
     */
    public function searchByBranchId(string $value, string|int $branchId): EloquentCollection;

    /**
     * Search all \App\Models\Customer records by branch id and user id.
     *
     * @param string $value
     * @param string|int $branchId
     * @param string|int $userId
     * @return EloquentCollection
     */
    public function searchByBranchIdAndUserId(string $value, string|int $branchId, string|int $userId): EloquentCollection;
}
