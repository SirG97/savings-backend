<?php

namespace App\Services;

use App\Actions\ResponseData;
use App\Contracts\CustomerRepositoryInterface;
use App\Contracts\CustomerTransactionRepositoryInterface;
use App\Contracts\CustomerWalletRepositoryInterface;
use App\Contracts\LoanApplicationRepositoryInterface;
use App\Contracts\TransactionRepositoryInterface;
use App\Contracts\WalletRepositoryInterface;
use App\Enums\LoanStatus;
use App\Enums\PaymentMethod;
use App\Enums\TransactionType;
use App\Enums\Type;
use App\Http\Requests\CustomerTransaction\CustomerTransactionCreateRequest;
use App\Http\Requests\CustomerTransaction\CustomerTransactionDeleteRequest;
use App\Http\Requests\CustomerTransaction\CustomerTransactionUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class CustomerTransactionService extends BasicCrudService
{

    public function __construct(private CustomerTransactionRepositoryInterface $customerTransactionRepository,
                                private CustomerRepositoryInterface $customerRepository,
                                private CustomerWalletRepositoryInterface $customerWalletRepository,
                                private LoanApplicationRepositoryInterface $loanApplicationRepository,
                                private WalletRepositoryInterface $walletRepository,
                                private TransactionRepositoryInterface $transactionRepository,
                                private TransactionService $transactionService)
    { }

    /**
     * Handle the create request.
     *
     * @param CustomerTransactionCreateRequest $request
     * @return ResponseData
     */
    public function handleCreate(CustomerTransactionCreateRequest $request): ResponseData
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::user()->id;
        $customer = $this->customerRepository->getById($validated['customer_id']);

        $validated['branch_id'] = $customer->branch_id;
        $wallet = $this->customerWalletRepository->getByCustomerId($validated['customer_id']);
        
        //Get outstanding loan
        if($loanApplication = $this->loanApplicationRepository->outstandingLoan($customer->id)){

            return $this->paybackLoan($customer, $wallet,$loanApplication, $validated);
        }
      
        $validated['reference'] = $this->generateReference();
        $validated['date'] ??= now();
        $validated['balance_before'] = $wallet->balance;

        if($validated['transaction_type'] === TransactionType::DEPOSIT->value){

            $validated['type'] = Type::CREDIT->value;
            $balance = $wallet->balance + (float)$validated['amount'];
            $validated['balance_after'] = $balance;

        }else{
            $validated['type'] = Type::DEBIT->value;
            $balance = $wallet->balance - (float)$validated['amount'];
            $validated['balance_after'] = $balance;

            if($wallet->balance < (float)$validated['amount']){
                var_dump($wallet->balance, $validated['amount']);
                return responseData(false, Response::HTTP_BAD_REQUEST,
                    'Insufficient cash balance');
            }
        }

        $response = $this->create($validated, $this->customerTransactionRepository);

        if(!$response->success){
            $this->delete(['id' => $response->data->id], $this->customerTransactionRepository);
            return responseData(false, Response::HTTP_INTERNAL_SERVER_ERROR,
                'Failed to create transaction for customer');
        }

        if(!$this->customerWalletRepository->update($wallet->id, ['balance' => $balance])){
            $this->delete(['id' => $response->data->id], $this->customerTransactionRepository);
            return responseData(false, Response::HTTP_INTERNAL_SERVER_ERROR,
                'Customer wallet could not be updated');
        }

        $branchTransaction = $this->transactionService->handleCreate($request);

        if(!$branchTransaction->success){
            $this->delete(['id' => $response->data->id], $this->customerTransactionRepository);
            return responseData(false, Response::HTTP_INTERNAL_SERVER_ERROR,
                $branchTransaction->message);
        }

        return $response;

    }

    /**
     * Handle the update request.
     *
     * @param  \App\Http\Requests\CustomerTransaction\CustomerTransactionUpdateRequest $request
     * @return ResponseData
     */
    public function handleUpdate(CustomerTransactionUpdateRequest $request): ResponseData
    {
        return $this->update($request, $this->customerTransactionRepository);
    }

    /**
     * Handle the delete request.
     *
     * @param  \App\Http\Requests\CustomerTransaction\CustomerTransactionDeleteRequest $request
     * @return ResponseData
     */
    public function handleDelete(CustomerTransactionDeleteRequest $request): ResponseData
    {
        return $this->delete($request, $this->customerTransactionRepository);
    }

    /**
     * Handle the read request.
     *
     * @param null|string|int $id
     * @return array
     */
    public function handleRead(null|string|int $id = null): ResponseData
    {
        return $this->read($this->customerTransactionRepository, 'customer_transaction', $id);
    }

    public function handleReadByTransactionType(TransactionType $transactionType, null|string|int $id = null): ResponseData
    {
        return $this->readByTransactionType($this->customerTransactionRepository, 'customer_transaction', $transactionType, $id);
    }

    public function handleReadByTransactionTypeAndCustomerId(TransactionType $transactionType, string|int $customerId, null|string|int $id = null): ResponseData
    {
        return $this->readByTransactionTypeAndCustomerId($this->customerTransactionRepository, 'customer_transaction', $transactionType,$customerId, $id);
    }

    public function handleReadByTransactionTypeAndUserId(TransactionType $transactionType, string|int $userId, null|string|int $id = null): ResponseData
    {
        return $this->readByTransactionTypeAndUserId($this->customerTransactionRepository, 'customer_transaction', $transactionType,$userId, $id);
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

    
    private function paybackLoan($customer, $wallet, $loanApplication, $validated): ResponseData
    {
        try {
            return DB::transaction(function () use ($customer, $wallet, $loanApplication, $validated) {
                $amountPaid = $validated['amount'];
                $loanAmount = $loanApplication->total_payable_amount;
    
                if ($amountPaid < $loanApplication->total_payable_amount) {
                    // Partial payment: Create loan_debit transaction
                    $this->updateLoanApplication($loanApplication, $amountPaid);
                    $this->creditBranchWithLoanAmount($loanApplication, $validated, $amountPaid);
                    $this->debitCustomerWithLoanAmount($loanApplication, $wallet, $validated, $amountPaid);
                   
                    return new ResponseData(
                        true,
                        Response::HTTP_OK,
                        'Partial loan payment recorded successfully'
                    );
                } elseif ($amountPaid == $loanApplication->total_payable_amount) {
                  
                    $this->updateLoanApplication($loanApplication, $amountPaid, LoanStatus::PAID->value);
                    // $branchAmount = $amountPaid - $loanApplication->interest_amount;
                     $this->creditBranchWithLoanAmount($loanApplication, $validated, $amountPaid);
                     $this->debitCustomerWithLoanAmount($loanApplication, $wallet, $validated, $amountPaid);
                    
                    return new ResponseData(
                        true,
                        Response::HTTP_OK,
                        'Loan fully paid and marked as completed'
                    );
                } else {
                    // Overpayment: Mark loan as completed, create loan_debit and loan_credit transactions
                    $this->updateLoanApplication($loanApplication, $loanAmount, LoanStatus::PAID->value);
                     /**
                      * collect 10, to payback 20, now I paid 50 
                    * remove total_payable from amount = 30
                      */
                    // Create loan_credit transaction for the excess amount
                    $excessAmount = $amountPaid - $loanAmount;
                    $this->creditBranchWithLoanAmount($loanApplication, $validated, $loanAmount);
                    $this->debitCustomerWithLoanAmount($loanApplication, $wallet, $validated, $loanAmount);


                    $this->creditCustomerWithExcessAmount($loanApplication, $wallet, $validated, $excessAmount);
                    $this->creditBranchWithExcessAmount($loanApplication, $validated, $excessAmount);
    
                    return new ResponseData(
                        true,
                        Response::HTTP_OK,
                        'Loan fully paid with excess amount credited'
                    );
                }
            });
        } catch (Throwable $e) {
            // Log the exception
            logger()->error('Loan payback failed: ' . $e->getMessage());
    
            // Return a failure response
            return new ResponseData(
                false,
                Response::HTTP_INTERNAL_SERVER_ERROR,
                'Loan payback failed' .  $e->getMessage()
            );
        }
    }
    
    /**
     * Create a transaction record.
     *
     * @param int $customerId
     * @param float $amount
     * @param TransactionType $transactionType
     * @return void
     */
    private function createTransaction(int $customerId, float $amount, TransactionType $transactionType): void
    {
        // Assuming you have a Transaction model and repository
        $this->transactionRepository->create([
            'customer_id' => $customerId,
            'amount' => $amount,
            'transaction_type' => $transactionType->value,
            'created_at' => Carbon::now(),
        ]);
    }
    
    /**
     * Mark the loan as completed in the loan applications table.
     *
     * @param int $customerId
     * @return void
     */
    private function updateLoanApplication($loanApplication, $amount, $status = null): void
    {
        $data = [];
        if(isset($status)){
            $data['status'] = $status;
        }
        $data['total_payable_amount'] = $loanApplication->total_payable_amount - $amount;
        // Assuming you have a LoanApplication model and repository
        $this->loanApplicationRepository->update($loanApplication->id, $data);
    }

    private function debitCustomerWithLoanAmount($loanApplication, $wallet, $validated, $amount):void{
        $data = [];
        $wallet =  $wallet = $this->customerWalletRepository->getByCustomerId($loanApplication->customer_id);
        $data['branch_id'] = $loanApplication->branch_id;
        $data['user_id'] = $loanApplication->user_id;
        $data['customer_id'] = $loanApplication->customer_id;
        $data['reference'] = $this->generateReference();
        $data['balance_before'] = $wallet->loan;
        $data['balance_after'] = $wallet->loan - $amount;
        $data['type'] = Type::DEBIT->value;
        $data['amount'] = $amount;
        $data['transaction_type'] = TransactionType::LOAN_DEBIT->value;
        $data['date'] = now();
        $data['description'] = "Loan payback";
        $data['payment_method'] = $validated['payment_method'];

        $this->customerWalletRepository->update($wallet->id, [
            'loan' => $wallet->loan - $amount
        ]);

        $this->customerTransactionRepository->create($data);
    }

    private function creditBranchWithLoanAmount($loanApplication, $validated, $amount):void{
        $data = [];
        $wallet = $this->walletRepository->getByBranchId($loanApplication->branch_id);
        $data['branch_id'] = $loanApplication->branch_id;
        $data['user_id'] = auth()->id();
        $data['customer_id'] = $loanApplication->customer_id;
        $data['reference'] = $this->generateBranchReference();
        $data['balance_before'] = $wallet->balance;
        $data['balance_after'] = $wallet->balance + $amount;
        $data['type'] = Type::CREDIT->value;
        $data['amount'] = $amount;
        $data['transaction_type'] = TransactionType::LOAN_CREDIT->value;
        $data['date'] = now();
        $data['description'] = "Loan payback";
        $data['payment_method'] = $validated['payment_method'];

        //Update wallet
        if($validated['payment_method'] === PaymentMethod::CASH->value){
            $walletData = [
                'balance' => $wallet->balance + $amount,
                'cash' => $wallet->cash + $amount,
                'loan' => $wallet->loan - $amount
            ];

        }else{
            $walletData = [
                'balance' => $wallet->balance +$amount,
                'bank' => $wallet->bank + $amount,
                'loan' => $wallet->loan - $amount
            ];
        }

        $this->walletRepository->update($wallet->id, $walletData);

        $this->transactionRepository->create($data);
      
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

    private function creditCustomerWithExcessAmount($loanApplication, $wallet, $validated, $amount):void{
        $data = [];

        $data['branch_id'] = $loanApplication->branch_id;
        $data['user_id'] = $loanApplication->user_id;
        $data['customer_id'] = $loanApplication->customer_id;
        $data['reference'] = $this->generateReference();
        $data['balance_before'] = $wallet->balance;
        $data['balance_after'] = $wallet->balance + $amount;
        $data['type'] = Type::CREDIT->value;
        $data['amount'] = $amount;
        $data['transaction_type'] = TransactionType::DEPOSIT->value;
        $data['date'] = now();
        $data['description'] = "Surplus from loan";
        $data['payment_method'] = $validated['payment_method'];

        $this->customerWalletRepository->update($wallet->id, [
            'balance' => $wallet->balance + $amount
        ]);

        $this->customerTransactionRepository->create($data);
    }

    private function creditBranchWithExcessAmount($loanApplication, $validated, $amount):void{
        $data = [];
        $wallet = $this->walletRepository->getByBranchId($loanApplication->branch_id);
        $data['branch_id'] = $loanApplication->branch_id;
        $data['user_id'] = $loanApplication->user_id;
        $data['customer_id'] = $loanApplication->customer_id;
        $data['reference'] = $this->generateBranchReference();
        $data['balance_before'] = $wallet->balance;
        $data['balance_after'] = $wallet->balance + $amount;
        $data['type'] = Type::CREDIT->value;
        $data['amount'] = $amount;
        $data['transaction_type'] = TransactionType::DEPOSIT->value;
        $data['date'] = now();
        $data['description'] = "Surplus from loan";
        $data['payment_method'] = $validated['payment_method'];

               //Update wallet
        if($validated['payment_method'] === PaymentMethod::CASH->value){
            $walletData = [
                'balance' => $wallet->balance + $amount,
                'cash' => $wallet->cash + $amount,
                
            ];

        }else{
            $walletData = [
                'balance' => $wallet->balance + $amount,
                'bank' => $wallet->bank + $amount,
                
            ];
        }

        $this->walletRepository->update($wallet->id, $walletData);

        $this->transactionRepository->create($data);
    }

}
