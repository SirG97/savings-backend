<?php

namespace App\Repositories;

use App\Contracts\LoanApplicationRepositoryInterface;
use App\Models\LoanApplication;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Pagination\LengthAwarePaginator;

class LoanApplicationRepository implements LoanApplicationRepositoryInterface
{

    /**
     * Fetch all \App\Models\LoanApplication records.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll(): EloquentCollection
    {
        return LoanApplication::all();
    }

    /**
     * Fetch \App\Models\LoanApplication record by ID.
     *
     * @param int $id
     * @return \App\Models\LoanApplication|null
     */
    public function getById(int $id): null|LoanApplication
    {
        return LoanApplication::find($id);
    }

    /**
     * Delete \App\Models\LoanApplication record by ID.
     *
     * @param int $id
     * @return void
     */
    public function delete(int $id): void
    {
        LoanApplication::destroy($id);
    }

    /**
     * Create \App\Models\LoanApplication record.
     *
     * @param array $arrayDetails
     * @return \App\Models\LoanApplication
     */
    public function create(array $arrayDetails): LoanApplication
    {
        return LoanApplication::create($arrayDetails);
    }

    /**
     * Fetch or create a single \App\Models\LoanApplication record.
     *
     * @param array $matchDetails
     * @param array $arrayDetails
     * @return \App\Models\LoanApplication
     */
    public function firstOrCreate(array $matchDetails, array $arrayDetails): LoanApplication
    {
        return LoanApplication::firstOrCreate($matchDetails, $arrayDetails);
    }

    /**
     * Update \App\Models\LoanApplication record.
     *
     * @param int $id
     * @param array $arrayDetails
     * @return int
     */
    public function update(int $id, array $arrayDetails): int
    {
        return LoanApplication::where('id', $id)->update($arrayDetails);
    }

    /**
     * Update \App\Models\LoanApplication record.
     *
     * @param int $pageSize
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getPaginated(int $pageSize): LengthAwarePaginator
    {
        return LoanApplication::paginate($pageSize);
    }
}
