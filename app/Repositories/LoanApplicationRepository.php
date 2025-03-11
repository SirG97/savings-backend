<?php

namespace App\Repositories;

use App\Contracts\LoanApplicationRepositoryInterface;
use App\Enums\LoanStatus;
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
     * @return LoanApplication|null
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
     * @return LoanApplication
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
     * @return LoanApplication
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

    /**
     * Update \App\Models\LoanApplication record.
     *
     * @param int $branchId
     * @param int $pageSize
     * @return LengthAwarePaginator
     */
    public function getByBranchIdPaginated(int $branchId, int $pageSize): LengthAwarePaginator
    {
        return LoanApplication::where('branch_id', $branchId)->paginate(pageSize($pageSize));
    }

    /**
     * Fetch all \App\Models\LoanApplication records.
     *
     * @param int $branchId
     * @return EloquentCollection
     */
    public function getByBranchId(int $branchId): EloquentCollection
    {
        return LoanApplication::where('branch_id', $branchId)->get();
    }

 

        /**
     * Update \App\Models\LoanApplication record.
     *
     * @param int $branchId
     * @param int $pageSize
     * @return LengthAwarePaginator
     */
    public function getByBranchIdAndStatusPaginated(int $branchId, string $status, int $pageSize): LengthAwarePaginator
    {
        return LoanApplication::where('branch_id', $branchId)->where('status', $status)->paginate(pageSize($pageSize));
    }

            /**
     * Update \App\Models\LoanApplication record.
     *
     * @param int $branchId
     * @param int $pageSize
     * @return LengthAwarePaginator
     */
    public function getByBranchIdAndStatus(int $branchId, string $status): EloquentCollection
    {
        return LoanApplication::where('branch_id', $branchId)->where('status', $status)->get();
    }

    /**
     * Fetch \App\Models\LoanApplication record by ID.
     *
     * @param int $branchId
     * @param int $id
     * @return LoanApplication|null
     */
    public function getByBranchIdAndId(int $branchId, int $id): null|LoanApplication
    {
        return LoanApplication::where('branch_id', $branchId)->where('id', $id)->first();
    }

    /**
     * Update \App\Models\LoanApplication record.
     *
     * @param int $userId
     * @param int $pageSize
     * @return LengthAwarePaginator
     */
    public function getByUserIdPaginated(int $userId, int $pageSize): LengthAwarePaginator
    {
        return LoanApplication::where('user_id', $userId)->paginate(pageSize($pageSize));
    }

        /**
     * Update \App\Models\LoanApplication record.
     *
     * @param int $userId
     * @param int $pageSize
     * @return LengthAwarePaginator
     */
    public function getByUserIdAndStatusPaginated(int $userId, string $status, int $pageSize): LengthAwarePaginator
    {
        return LoanApplication::where('user_id', $userId)->where('status', $status)->paginate(pageSize($pageSize));
    }

            /**
     * Update \App\Models\LoanApplication record.
     *
     * @param int $userId
     * @param int $pageSize
     * @return LengthAwarePaginator
     */
    public function getByUserIdAndStatus(int $userId, string $status): EloquentCollection
    {
        return LoanApplication::where('user_id', $userId)->where('status', $status)->get();
    }

    /**
     * Fetch all \App\Models\LoanApplication records.
     *
     * @param int $userId
     * @return EloquentCollection
     */
    public function getByUserId(int $userId): EloquentCollection
    {
        return LoanApplication::where('user_id', $userId)->get();
    }


    /**
     * Fetch all \App\Models\LoanApplication records.
     *
     * @param int $customerId
     * @return EloquentCollection
     */
    public function getByCustomerId(int $customerId): EloquentCollection
    {
        return LoanApplication::where('customer_id', $customerId)->get();
    }


    /**
     * Fetch \App\Models\LoanApplication record by ID.
     *
     * @param int $userId
     * @param int $id
     * @return LoanApplication|null
     */
    public function getByUserIdAndId(int $userId, int $id): null|LoanApplication
    {
        return LoanApplication::where('user_id', $userId)->where('id',$id)->first();
    }


    /**
     * Get \App\Models\LoanApplication record.
     *
     * @param int $customerId
     * @param int $pageSize
     * @return LengthAwarePaginator
     */
    public function getByCustomerIdPaginated(int $customerId, int $pageSize): LengthAwarePaginator
    {
        return LoanApplication::where('customer_id', $customerId)->paginate(pageSize($pageSize));
    }

    /**
     * Fetch \App\Models\LoanApplication record by ID.
     *
     * @param int $id
     * @return LoanApplication|null
     */
    public function checkPendingLoanByCustomerId(int $id): null|LoanApplication
    {
        return LoanApplication::where('customer_id', $id)->where('status',LoanStatus::PENDING->value)->first();

    }

        /**
     * Fetch \App\Models\LoanApplication record by ID.
     *
     * @param int $id
     * @return LoanApplication|null
     */
    public function outstandingLoan(int $id): null|LoanApplication
    {
        return LoanApplication::whereIn('status',[
            LoanStatus::DUE->value,
            LoanStatus::OVERDUE->value,
            LoanStatus::APPROVED->value])
        ->where('customer_id', $id)
        ->first();

    }

}
