<?php

namespace App\Repositories;

use App\Contracts\LoanRepositoryInterface;
use App\Models\Loan;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Pagination\LengthAwarePaginator;

class LoanRepository implements LoanRepositoryInterface
{

    /**
     * Fetch all \App\Models\Loan records.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll(): EloquentCollection
    {
        return Loan::all();
    }

    /**
     * Fetch \App\Models\Loan record by ID.
     *
     * @param int $id
     * @return \App\Models\Loan|null
     */
    public function getById(int $id): null|Loan
    {
        return Loan::find($id);
    }

    /**
     * Delete \App\Models\Loan record by ID.
     *
     * @param int $id
     * @return void
     */
    public function delete(int $id): void
    {
        Loan::destroy($id);
    }

    /**
     * Create \App\Models\Loan record.
     *
     * @param array $arrayDetails
     * @return \App\Models\Loan
     */
    public function create(array $arrayDetails): Loan
    {
        return Loan::create($arrayDetails);
    }

    /**
     * Fetch or create a single \App\Models\Loan record.
     *
     * @param array $matchDetails
     * @param array $arrayDetails
     * @return \App\Models\Loan
     */
    public function firstOrCreate(array $matchDetails, array $arrayDetails): Loan
    {
        return Loan::firstOrCreate($matchDetails, $arrayDetails);
    }

    /**
     * Update \App\Models\Loan record.
     *
     * @param int $id
     * @param array $arrayDetails
     * @return int
     */
    public function update(int $id, array $arrayDetails): int
    {
        return Loan::where('id', $id)->update($arrayDetails);
    }

    /**
     * Update \App\Models\Loan record.
     *
     * @param int $pageSize
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getPaginated(int $pageSize): LengthAwarePaginator
    {
        return Loan::paginate($pageSize);
    }
}
