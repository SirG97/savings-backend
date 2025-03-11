<?php

namespace App\Services;

use App\Actions\ResponseData;
use App\Contracts\CustomerRepositoryInterface;
use App\Contracts\CustomerTransactionRepositoryInterface;
use App\Contracts\CustomerWalletRepositoryInterface;
use App\Contracts\LoanApplicationRepositoryInterface;
use App\Contracts\LoanRepositoryInterface;
use App\Contracts\TransactionRepositoryInterface;
use App\Contracts\WalletRepositoryInterface;
use App\Enums\LoanStatus;
use App\Enums\PaymentMethod;
use App\Enums\PerformedAction;
use App\Enums\TransactionType;
use App\Enums\Type;
use App\Http\Requests\LoanApplication\LoanApplicationCreateRequest;
use App\Http\Requests\LoanApplication\LoanApplicationDeleteRequest;
use App\Http\Requests\LoanApplication\LoanApplicationUpdateRequest;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class LoanApplicationService extends BasicCrudService
{
    public function __construct(
        private LoanApplicationRepositoryInterface $loanApplicationRepository,
        private LoanRepositoryInterface $loanRepository,
        private CustomerRepositoryInterface $customerRepository,
        private CustomerTransactionRepositoryInterface $customerTransactionRepository,
        private TransactionRepositoryInterface $transactionRepository,
        private CustomerWalletRepositoryInterface $customerWalletRepository,
        private WalletRepositoryInterface $walletRepository,

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

        if($pendingLoan = $this->loanApplicationRepository->checkPendingLoanByCustomerId($validated['customer_id'])){
            return responseData(false, Response::HTTP_UNPROCESSABLE_ENTITY,'Customer already has a pending loan. Please approve or reject it first before applying another one.' );
        }

        if($isEligible = $this->loanApplicationRepository->outstandingLoan($validated['customer_id'])){
            return responseData(false, Response::HTTP_UNPROCESSABLE_ENTITY,'You cannot apply for loan if you have an approved, due or overdue loan' );
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
            return $this->handleApproval($validated['id'], $validated);
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
     * Handle the read request.
     *
     * @param null|string|int $id
     * @return array
     */
    public function handleReadByUserIdAndStatus(int $userId, string $status, null|string|int $id = null): ResponseData
    {
        return $this->read($this->loanApplicationRepository, 'loan_application', $id);
    }

    /**
     * Handle loan application approval
     *
     * @param int $id
     * @return \App\Actions\ResponseData
     */
    private function handleApproval(int $id, array $validated): ResponseData
    {
        $loanApplication = $this->loanApplicationRepository->getById($id);
    
        if ($loanApplication->status !== LoanStatus::PENDING->value) {
            return new ResponseData(false, Response::HTTP_UNPROCESSABLE_ENTITY, 'Only pending applications can be approved');
        }

        $wallet =  $wallet = $this->walletRepository->getByBranchId($loanApplication->branch_id);
        
        if($validated['payment_method'] === PaymentMethod::CASH->value and
            $wallet->cash < (float)$loanApplication->amount){
            return responseData(false, Response::HTTP_BAD_REQUEST,
                'Insufficient cash balance to approve loan');
        }

        if($validated['payment_method'] === PaymentMethod::BANK->value and
            $wallet->bank < (float)$loanApplication->amount){
            return responseData(false, Response::HTTP_BAD_REQUEST,
                'Insufficient bank balance to approve loan');
        }

        try {
            return DB::transaction(function () use ($id, $validated, $loanApplication) {
                $dueDate = Carbon::now()->addMonths($loanApplication->duration);
    
                $this->loanApplicationRepository->update($id, [
                    'status' => LoanStatus::APPROVED->value,
                    'approved_at' => Carbon::now(),
                    'due_date' => $dueDate,
                    'approved_by' => auth()->id()
                ]);
    
                // Create transaction for customer and branch
                $this->debitBranchWithLoanAmount($loanApplication, $validated);
                $this->creditCustomerWithLoanAmount($loanApplication, $validated);
    
                return new ResponseData(
                    true,
                    Response::HTTP_OK,
                    'Loan application approved successfully'
                );
            });
        } catch (Throwable $e) {
            // Log the exception if needed
            logger()->error('Loan application approval failed: ' . $e->getMessage());
    
            // Return a failure response
            return new ResponseData(
                false,
                Response::HTTP_UNPROCESSABLE_ENTITY,
                'Loan application approval failed'
            );
        }
    }

    /**
     * Handle loan application rejection
     *
     * @param int $id
     * @param string $reason
     * @return \App\Actions\ResponseData
     */
    private function handleRejection(int $id, string $reason): ResponseData
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

    private function creditCustomerWithLoanAmount($loanApplication, $validated):bool{
        $data = [];
        $wallet =  $wallet = $this->customerWalletRepository->getByCustomerId($loanApplication->customer_id);
        $data['branch_id'] = $loanApplication->branch_id;
        $data['user_id'] = $loanApplication->user_id;
        $data['customer_id'] = $loanApplication->customer_id;
        $data['reference'] = $this->generateReference();
        $data['balance_before'] = $wallet->balance;
        $data['balance_after'] = $wallet->balance + $loanApplication->amount;
        $data['type'] = Type::CREDIT->value;
        $data['amount'] = $loanApplication->amount;
        $data['transaction_type'] = TransactionType::LOAN_CREDIT->value;
        $data['date'] = now();
        $data['description'] = "Loan approved and disbursed";
        $data['payment_method'] = $validated['payment_method'];

        if(!$this->customerWalletRepository->update($wallet->id, [
            // 'balance' => $wallet->balance + $loanApplication->amount,
            'loan' => $wallet->loan + $loanApplication->total_amount
        ])){
            return false;
        }

        if(!$this->customerTransactionRepository->create($data)){
            $this->customerWalletRepository->update($wallet->id, [
                'balance' => $wallet->balance - $loanApplication->amount,
                'loan' => $wallet->loan - $loanApplication->total_amount
            ]);
            return false;
        }

        return true;
    }

    private function debitBranchWithLoanAmount($loanApplication, $validated):bool{
        $data = [];
        $wallet =  $wallet = $this->walletRepository->getByBranchId($loanApplication->branch_id);
        $data['branch_id'] = $loanApplication->branch_id;
        $data['user_id'] = $loanApplication->user_id;
        $data['customer_id'] = $loanApplication->customer_id;
        $data['reference'] = $this->generateBranchReference();
        $data['balance_before'] = $wallet->balance;
        $data['balance_after'] = $wallet->balance - $loanApplication->amount;
        $data['type'] = Type::DEBIT->value;
        $data['amount'] = $loanApplication->amount;
        $data['transaction_type'] = TransactionType::LOAN_DEBIT->value;
        $data['date'] = now();
        $data['description'] = "Loan approved and disbursed";
        $data['payment_method'] = $validated['payment_method'];

                    //Update wallet
        if($validated['payment_method'] === PaymentMethod::CASH->value){
            $walletData = [
                'balance' => $wallet->balance - $loanApplication->amount,
                'cash' => $wallet->cash - $loanApplication->amount,
                'loan' => $wallet->loan + $loanApplication->total_amount
            ];

        }else{
            $walletData = [
                'balance' => $wallet->balance - $loanApplication->amount,
                'bank' => $wallet->bank - $loanApplication->amount,
                'loan' => $wallet->loan + $loanApplication->total_mount
            ];
        }

        if(!$this->walletRepository->update($wallet->id, $walletData)){
            return false;
        }

        if(!$this->transactionRepository->create($data)){
        
            return false;
        }
     
        return true;
      
    }

    private function generateReference(): string
    {
        $permitted_string = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $length = 10;
        $input_length = strlen($permitted_string);
        $random_string = '';
        for($i = 0; $i < $length; $i++) {
            $random_character = $permitted_string[mt_rand(0, $input_length - 1)];
            $random_string .= $random_character;
        }

        if($reference = $this->customerTransactionRepository->getByReference($random_string)){
            $this->generateReference();
        }

        return $random_string;
    }

    private function generateBranchReference(): string
    {
        $permitted_string = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $length = 10;
        $input_length = strlen($permitted_string);
        $random_string = '';
        for($i = 0; $i < $length; $i++) {
            $random_character = $permitted_string[mt_rand(0, $input_length - 1)];
            $random_string .= $random_character;
        }

        if($reference = $this->transactionRepository->getByReference($random_string)){
            $this->generateReference();
        }

        return $random_string;
    }


    /**
     * Handle the read request by user id.
     *
     * @param null|string|int $id
     * @return array
     */
    public function handleReadByUserId($userId, $status): ResponseData
    {

        if (isset($status)) {
            return responseData(true, Response::HTTP_OK, trans('crud.read'),
                $this->loanApplicationRepository->getByUserIdAndStatusPaginated($userId, $status, config("api.paginate.loan_application.pageSize")));
        }


        return responseData(true, Response::HTTP_OK, trans('crud.read'),
            $this->loanApplicationRepository->getByUserIdPaginated($userId, config("api.paginate.loan_application.pageSize")));

      
    }


    /**
     * Handle the read request by branch id.
     *
     * @param null|string|int $id
     * @return array
     */
    public function handleReadByBranchId($branchId, $status): ResponseData
    {
        if (isset($status)) {
            return responseData(true, Response::HTTP_OK, trans('crud.read'),
                $this->loanApplicationRepository->getByBranchIdAndStatusPaginated($branchId, $status, config("api.paginate.loan_application.pageSize")));
        }


        return responseData(true, Response::HTTP_OK, trans('crud.read'),
            $this->loanApplicationRepository->getByBranchIdPaginated($branchId, config("api.paginate.loan_application.pageSize")));

    }

    /**
     * Handle the read request by branch id.
     *
     * @param null|string|int $id
     * @return array
     */
    public function handleReadByCustomerId(null|string|int $id = null): ResponseData
    {
        return $this->readByCustomerId($this->loanApplicationRepository, 'loan_application', $id);
    }
}
