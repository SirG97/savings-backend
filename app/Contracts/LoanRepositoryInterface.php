<?php

namespace App\Contracts;

use App\Models\Loan;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Pagination\LengthAwarePaginator;

interface LoanRepositoryInterface
{

    /**
     * Fetch all \App\Models\Loan records.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll(): EloquentCollection;

    /**
     * Fetch \App\Models\Loan record by ID.
     *
     * @param int $id
     * @return \App\Models\Loan|null
     */
    public function getById(int $id): null|Loan;

    /**
     * Delete \App\Models\Loan record by ID.
     *
     * @param int $id
     * @return void
     */
    public function delete(int $id): void;

    /**
     * Create \App\Models\Loan record.
     *
     * @param array $arrayDetails
     * @return \App\Models\Loan
     */
    public function create(array $arrayDetails): Loan;

    /**
     * Fetch or create a single \App\Models\Loan record.
     *
     * @param array $matchDetails
     * @param array $arrayDetails
     * @return \App\Models\Loan
     */
    public function firstOrCreate(array $matchDetails, array $arrayDetails): Loan;

    /**
     * Update \App\Models\Loan record.
     *
     * @param int $id
     * @param array $arrayDetails
     * @return int
     */
    public function update(int $id, array $arrayDetails): int;

    /**
     * Update \App\Models\Loan record.
     *
     * @param int $pageSize
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getPaginated(int $pageSize): LengthAwarePaginator;
}
