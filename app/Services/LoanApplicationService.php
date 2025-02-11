<?php

namespace App\Services;

use App\Actions\ResponseData;
use App\Contracts\CustomerRepositoryInterface;
use App\Contracts\LoanApplicationRepositoryInterface;
use App\Contracts\LoanRepositoryInterface;
use App\Enums\LoanStatus;
use App\Enums\PerformedAction;
use App\Http\Requests\LoanApplication\LoanApplicationCreateRequest;
use App\Http\Requests\LoanApplication\LoanApplicationDeleteRequest;
use App\Http\Requests\LoanApplication\LoanApplicationUpdateRequest;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class LoanApplicationService extends BasicCrudService
{
    public function __construct(
        private LoanApplicationRepositoryInterface $loanApplicationRepository,
        private LoanRepositoryInterface $loanRepository,
        private CustomerRepositoryInterface $customerRepository

    ) { }

    /**
     * Handle the create request.
     *
     * @param  \App\Http\Requests\LoanApplication\LoanApplicationCreateRequest $request
     * @return \App\Actions\ResponseData
     */
    public function handleCreate(LoanApplicationCreateRequest $request): ResponseData
    {
        $validated = $request->validated();

        $loan = $this->loanRepository->getById(1);
        $customer = $this->customerRepository->getById($validated['customer_id']);

        if (!$loan) {
            return responseData(false, Response::HTTP_UNPROCESSABLE_ENTITY,'Loan not available' );
        }

        // Calculate interest for the entire loan duration
        // For multi-month loans, interest is calculated per month
        $interest = ($request->amount * $loan->interest_rate / 100) * (int)$validated['duration'];
        $totalPayable = (float)$request->amount + $interest;

        $validated['user_id'] = Auth::user()->id;
        $validated['branch_id'] = Auth::user()->branch_id ?? $customer->branch_id;
        $validated['loan_id'] = $loan->id;
        $validated['interest_amount'] = $interest;
        $validated['total_amount'] = $totalPayable;
        $validated['total_payable_amount'] = $totalPayable;
        $validated['status'] = LoanStatus::PENDING->value;

        return $this->create($validated, $this->loanApplicationRepository);
    }

    /**
     * Handle the update request.
     *
     * @param  \App\Http\Requests\LoanApplication\LoanApplicationUpdateRequest $request
     * @return \App\Actions\ResponseData
     */
    public function handleUpdate(LoanApplicationUpdateRequest $request): ResponseData
    {
        $validated = $request->validated();
        if($validated['status'] == PerformedAction::APPROVED->value){
            return $this->handleApproval($validated['id']);
        }
        return $this->handleRejection($validated['id'],$validated['reason']);
    }

    /**
     * Handle the delete request.
     *
     * @param  \App\Http\Requests\LoanApplication\LoanApplicationDeleteRequest $request
     * @return \App\Actions\ResponseData
     */
    public function handleDelete(LoanApplicationDeleteRequest $request): ResponseData
    {
        return $this->delete($request, $this->loanApplicationRepository);
    }

    /**
     * Handle the read request.
     *
     * @param null|string|int $id
     * @return array
     */
    public function handleRead(null|string|int $id = null): ResponseData
    {
        return $this->read($this->loanApplicationRepository, 'loan_application', $id);
    }

    /**
     * Handle loan application approval
     *
     * @param int $id
     * @return \App\Actions\ResponseData
     */
    public function handleApproval(int $id): ResponseData
    {
        $loanApplication = $this->loanApplicationRepository->getById($id);

        if ($loanApplication->status !== LoanStatus::PENDING->value) {
            return new ResponseData(false, Response::HTTP_UNPROCESSABLE_ENTITY, 'Only pending applications can be approved',);
        }

        $dueDate = Carbon::now()->addMonths($loanApplication->duration);

        $this->loanApplicationRepository->update($id, [
            'status' => LoanStatus::APPROVED->value,
            'approved_at' => Carbon::now(),
            'due_date' => $dueDate
        ]);

        return new ResponseData(
           true,
            Response::HTTP_OK,
            'Loan application approved successfully',

        );
    }

    /**
     * Handle loan application rejection
     *
     * @param int $id
     * @param string $reason
     * @return \App\Actions\ResponseData
     */
    public function handleRejection(int $id, string $reason): ResponseData
    {
        $loanApplication = $this->loanApplicationRepository->getById($id);

        if (!$loanApplication) {
            return new ResponseData(false, Response::HTTP_NOT_FOUND, 'Loan application not found',);
        }

        if ($loanApplication->status !== LoanStatus::PENDING->value) {
            return new ResponseData(false, Response::HTTP_UNPROCESSABLE_ENTITY, 'Only pending applications can be rejected',);
        }

        $this->loanApplicationRepository->update($id, [
            'status' => LoanStatus::REJECTED->value,
            'rejected_at' => Carbon::now(),
            'rejection_reason' => $reason
        ]);

        return new ResponseData(
            true,
            Response::HTTP_OK,
            'Loan application rejected successfully',

        );
    }


    /**
     * Handle the read request by user id.
     *
     * @param null|string|int $id
     * @return array
     */
    public function handleReadByUserId(null|string|int $id = null): ResponseData
    {
        return $this->readByUserId($this->loanApplicationRepository, 'loan_application', $id);
    }


    /**
     * Handle the read request by branch id.
     *
     * @param null|string|int $id
     * @return array
     */
    public function handleReadByBranchId(null|string|int $id = null): ResponseData
    {
        return $this->readByBranchId($this->loanApplicationRepository, 'loan_application', $id);
    }

    /**
     * Handle the read request by branch id.
     *
     * @param null|string|int $id
     * @return array
     */
    public function handleReadByCustomerId(null|string|int $id = null): ResponseData
    {
        return $this->read($this->loanApplicationRepository, 'loan_application', $id);
    }
}
