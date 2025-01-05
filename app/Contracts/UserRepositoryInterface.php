<?php

namespace App\Contracts;

use App\Enums\UserModelType;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{

    /**
     * Fetch all \App\Models\User records.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll(): EloquentCollection;

    /**
     * Fetch \App\Models\User record by ID.
     *
     * @param int $id
     * @return \App\Models\User|null
     */
    public function getById(int $id): null|User;

    /**
     * Delete \App\Models\User record by ID.
     *
     * @param int $id
     * @return void
     */
    public function delete(int $id): void;

    /**
     * Create \App\Models\User record.
     *
     * @param array $arrayDetails
     * @return \App\Models\User
     */
    public function create(array $arrayDetails): User;

    /**
     * Fetch or create a single \App\Models\User record.
     *
     * @param array $matchDetails
     * @param array $arrayDetails
     * @return \App\Models\User
     */
    public function firstOrCreate(array $matchDetails, array $arrayDetails): User;

    /**
     * Update \App\Models\User record.
     *
     * @param int $id
     * @param array $arrayDetails
     * @return int
     */
    public function update(int $id, array $arrayDetails): int;

    /**
     * Update \App\Models\User record.
     *
     * @param int $pageSize
     * @return LengthAwarePaginator
     */
    public function getPaginated(int $pageSize): LengthAwarePaginator;

    /**
     * Get \App\Models\User record by model paginated.
     *
     * @param UserModelType $model
     * @param int $pageSize
     * @return LengthAwarePaginator
     */
    public function getByUserModelPaginated(UserModelType $model, int $pageSize): LengthAwarePaginator;

    /**
     * Fetch all \App\Models\User records by user model.
     *
     * @param UserModelType $model
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getByUserModel(UserModelType $model): EloquentCollection;

    /**
     * Fetch \App\Models\User record(s) by email.
     *
     * @param string $email
     * @return mixed
     */
    public function getByEmail(string $email): mixed;


    /**
     * Search all \App\Models\User records.
     *
     * @param string $value
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function search(string $value): EloquentCollection;

    /**
     * Query builder
     *
     * @return Builder
     */
    public function queryBuilder(): Builder;

    /**
     * Fetch, update or create a single \App\Models\User record.
     *
     * @param array $matchDetails
     * @param array $arrayDetails
     * @return \App\Models\User
     */
    public function updateOrCreate(array $matchDetails, array $arrayDetails): User;

    /**
     * Update \App\Models\User record.
     *
     * @param int $branchId
     * @param int $pageSize
     * @return LengthAwarePaginator
     */
    public function getByBranchIdPaginated(int $branchId, int $pageSize): LengthAwarePaginator;

    /**
     * Fetch all \App\Models\User records.
     *
     * @param int $branchId
     * @return EloquentCollection
     */
    public function getByBranchId(int $branchId): EloquentCollection;

    /**
     * Fetch \App\Models\User record by ID.
     *
     * @param int $branchId
     * @param int $id
     * @return User|null
     */
    public function getByBranchIdAndId(int $branchId, int $id): null|User;
}
