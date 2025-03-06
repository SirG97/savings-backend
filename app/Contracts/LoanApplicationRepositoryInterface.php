<?php

namespace App\Contracts;

use App\Models\LoanApplication;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Pagination\LengthAwarePaginator;

interface LoanApplicationRepositoryInterface
{

    /**
     * Fetch all \App\Models\LoanApplication records.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll(): EloquentCollection;

    /**
     * Fetch \App\Models\LoanApplication record by ID.
     *
     * @param int $id
     * @return \App\Models\LoanApplication|null
     */
    public function getById(int $id): null|LoanApplication;

    /**
     * Delete \App\Models\LoanApplication record by ID.
     *
     * @param int $id
     * @return void
     */
    public function delete(int $id): void;

    /**
     * Create \App\Models\LoanApplication record.
     *
     * @param array $arrayDetails
     * @return \App\Models\LoanApplication
     */
    public function create(array $arrayDetails): LoanApplication;

    /**
     * Fetch or create a single \App\Models\LoanApplication record.
     *
     * @param array $matchDetails
     * @param array $arrayDetails
     * @return \App\Models\LoanApplication
     */
    public function firstOrCreate(array $matchDetails, array $arrayDetails): LoanApplication;

    /**
     * Update \App\Models\LoanApplication record.
     *
     * @param int $id
     * @param array $arrayDetails
     * @return int
     */
    public function update(int $id, array $arrayDetails): int;

    /**
     * Update \App\Models\LoanApplication record.
     *
     * @param int $pageSize
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getPaginated(int $pageSize): LengthAwarePaginator;

    /**
     * Fetch \App\Models\LoanApplication record by ID.
     *
     * @param int $id
     * @return \App\Models\LoanApplication|null
     */
    public function checkPendingLoanByCustomerId(int $id): null|LoanApplication;

            /**
     * Update \App\Models\LoanApplication record.
     *
     * @param int $userId
     * @param int $pageSize
     * @return LengthAwarePaginator
     */
    public function getByUserIdAndStatusPaginated(int $userId, string $status, int $pageSize): LengthAwarePaginator;


        /**
     * Fetch all \App\Models\LoanApplication records.
     *
     * @param int $branchId
     * @return EloquentCollection
     */
    public function getByBranchIdAndStatus(int $branchId, string $status): EloquentCollection;


        /**
     * Update \App\Models\LoanApplication record.
     *
     * @param int $branchId
     * @param int $pageSize
     * @return LengthAwarePaginator
     */
    public function getByBranchIdAndStatusPaginated(int $branchId, string $status, int $pageSize): LengthAwarePaginator;

                /**
     * Update \App\Models\LoanApplication record.
     *
     * @param int $userId
     * @param int $pageSize
     * @return LengthAwarePaginator
     */
    public function getByUserIdAndStatus(int $userId, string $status): EloquentCollection;

        /**
     * Update \App\Models\LoanApplication record.
     *
     * @param int $userId
     * @param int $pageSize
     * @return LengthAwarePaginator
     */
    public function getByUserIdPaginated(int $userId, int $pageSize): LengthAwarePaginator;

    /**
     * Fetch \App\Models\LoanApplication record by ID.
     *
     * @param int $id
     * @return LoanApplication|null
     */
    public function isCustomerEligible(int $id): null|LoanApplication;

    
}
