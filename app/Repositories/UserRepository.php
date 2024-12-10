<?php

namespace App\Repositories;

use App\Contracts\UserRepositoryInterface;
use App\Enums\UserModel;
use App\Enums\UserModelType;
use App\Models\Admin;
use App\Models\Marketer;
use App\Models\User;
use App\Traits\TableFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository implements UserRepositoryInterface
{
    private array $whiteList = [];

    use TableFilter;
    /**
     * Fetch all \App\Models\User records.
     *
     * @return EloquentCollection
     */
    public function getAll(): EloquentCollection
    {
        return User::all();
    }

    /**
     * Fetch \App\Models\User record by ID.
     *
     * @param int $id
     * @return \App\Models\User|null
     */
    public function getById(int $id): null|User
    {
        return User::find($id);
    }

    /**
     * Delete \App\Models\User record by ID.
     *
     * @param int $id
     * @return void
     */
    public function delete(int $id): void
    {
        User::destroy($id);
    }

    /**
     * Create \App\Models\User record.
     *
     * @param array $arrayDetails
     * @return \App\Models\User
     */
    public function create(array $arrayDetails): User
    {
        return User::create($arrayDetails);
    }

    /**
     * Fetch or create a single \App\Models\User record.
     *
     * @param array $matchDetails
     * @param array $arrayDetails
     * @return \App\Models\User
     */
    public function firstOrCreate(array $matchDetails, array $arrayDetails): User
    {
        return User::firstOrCreate($matchDetails, $arrayDetails);
    }

    /**
     * Update \App\Models\User record.
     *
     * @param int $id
     * @param array $arrayDetails
     * @return int
     */
    public function update(int $id, array $arrayDetails): int
    {
        return User::where('id', $id)->update($arrayDetails);
    }

    /**
     * Update \App\Models\User record.
     *
     * @param int $pageSize
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getPaginated(int $pageSize): LengthAwarePaginator
    {
        return User::paginate($pageSize);
    }

    /**
     * Get \App\Models\User record by model paginated.
     *
     * @param UserModelType $model
     * @param int $pageSize
     * @return LengthAwarePaginator
     */
    public function getByUserModelPaginated(UserModelType $model, int $pageSize): LengthAwarePaginator
    {
        return User::where('model', UserModel::getType($model->value))->paginate(pageSize($pageSize));
    }

    /**
     * Fetch all \App\Models\User records by user model.
     *
     * @param UserModelType $model
     * @return EloquentCollection
     */
    public function getByUserModel(UserModelType $model): EloquentCollection
    {

        return User::where('model', UserModel::getType($model->value))->get();
    }

    /**
     * Fetch \App\Models\User record(s) by email.
     *
     * @param string $email
     * @return mixed
     */
    public function getByEmail(string $email): mixed
    {
        return User::where('email', $email);
    }

    /**
     * Fetch \App\Models\User record(s) by unit number.
     *
     * @param string $unitNumber
     * @return mixed
     */
    public function getByUnitNumber(string $unitNumber): mixed
    {
        return User::where('unit_number', $unitNumber);
    }

    /**
     * Search all \App\Models\User records.
     *
     * @param string $value
     * @return EloquentCollection
     */
    public function search(string $value): EloquentCollection
    {
        return User::search($value)->where('model', Marketer::class)->get();
    }

    /**
     * Query builder
     *
     * @param string $table
     * @return Builder
     */
    public function queryBuilder(): Builder
    {
        return User::query();
    }

    /**
     * Fetch, update or create a single \App\Models\User record.
     *
     * @param array $matchDetails
     * @param array $arrayDetails
     * @return \App\Models\User
     */
    public function updateOrCreate(array $matchDetails, array $arrayDetails): User
    {
        return User::updateOrCreate($matchDetails, $arrayDetails);
    }

}
