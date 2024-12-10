<?php

namespace App\Contracts;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Pagination\LengthAwarePaginator;

interface BranchRepositoryInterface
{

    /**
     * Fetch all \App\Models\Branch records.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll(): EloquentCollection;

    /**
     * Fetch \App\Models\Branch record by ID.
     *
     * @param int $id
     * @return \App\Models\Branch|null
     */
    public function getById(int $id): null|Branch;

    /**
     * Delete \App\Models\Branch record by ID.
     *
     * @param int $id
     * @return void
     */
    public function delete(int $id): void;

    /**
     * Create \App\Models\Branch record.
     *
     * @param array $arrayDetails
     * @return \App\Models\Branch
     */
    public function create(array $arrayDetails): Branch;

    /**
     * Fetch or create a single \App\Models\Branch record.
     *
     * @param array $matchDetails
     * @param array $arrayDetails
     * @return \App\Models\Branch
     */
    public function firstOrCreate(array $matchDetails, array $arrayDetails): Branch;

    /**
     * Update \App\Models\Branch record.
     *
     * @param int $id
     * @param array $arrayDetails
     * @return int
     */
    public function update(int $id, array $arrayDetails): int;

    /**
     * Update \App\Models\Branch record.
     *
     * @param int $pageSize
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getPaginated(int $pageSize): LengthAwarePaginator;
}
