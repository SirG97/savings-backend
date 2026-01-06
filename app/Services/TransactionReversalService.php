<?php

namespace App\Services;

use App\Actions\ResponseData;
use App\Enums\PaymentMethod;
use App\Enums\TransactionType;
use App\Enums\Type;
use App\Models\CustomerTransaction;
use App\Models\Transaction;
use App\Repositories\CustomerTransactionRepository;
use App\Repositories\CustomerWalletRepository;
use App\Repositories\TransactionRepository;
use App\Repositories\WalletRepository;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TransactionReversalService extends BasicCrudService
{
    public function __construct(
        private CustomerTransactionRepository $customerTransactionRepository,
        private TransactionRepository $transactionRepository,
        private CustomerWalletRepository $customerWalletRepository,
        private WalletRepository $walletRepository
    ) {}

    public function reverseCustomerTransaction(int $transactionId): ResponseData
    {
        $originalTransaction = $this->customerTransactionRepository->getById($transactionId);
        
        if (!$originalTransaction) {
            return responseData(false, Response::HTTP_NOT_FOUND, 'Transaction not found');
        }
        
        if ($originalTransaction->reversed_by) {
            return responseData(false, Response::HTTP_BAD_REQUEST, 'Transaction already reversed');
        }
        
        if ($originalTransaction->transaction_type === TransactionType::REVERSAL->value) {
            return responseData(false, Response::HTTP_BAD_REQUEST, 'Cannot reverse a reversal transaction');
        }

        // Create reversal transaction with original payment method
        $reversalData = $this->prepareReversalData($originalTransaction);
        
        $reversedTransaction = $this->customerTransactionRepository->create($reversalData);
        
        if (!$reversedTransaction) {
            return responseData(false, Response::HTTP_INTERNAL_SERVER_ERROR, 'Failed to create reversal transaction');
        }

        // Update original transaction to point to this reversal
        $this->customerTransactionRepository->update($originalTransaction->id, [
            'reversed_by' => $reversedTransaction->id
        ]);

        // Update customer wallet
        $wallet = $this->customerWalletRepository->getByCustomerId($originalTransaction->customer_id);
        
        if ($originalTransaction->type === Type::CREDIT->value) {
            $newBalance = $wallet->balance - $originalTransaction->amount;
        } else {
            $newBalance = $wallet->balance + $originalTransaction->amount;
        }
        
        if (!$this->customerWalletRepository->update($wallet->id, ['balance' => $newBalance])) {
            // Rollback if wallet update fails
            $this->customerTransactionRepository->delete($reversedTransaction->id);
            return responseData(false, Response::HTTP_INTERNAL_SERVER_ERROR, 'Failed to update customer wallet');
        }

        // If this was a customer transaction that had a corresponding branch transaction
        if ($originalTransaction->transaction_type !== TransactionType::COMMISSION->value) {
            $branchTransaction = $this->transactionRepository->getByReference($originalTransaction->reference);
            
            if ($branchTransaction) {
                $branchReversalResult = $this->reverseBranchTransaction($branchTransaction);
                
                if (!$branchReversalResult->success) {
                    // Rollback customer reversal if branch reversal fails
                    $this->customerWalletRepository->update($wallet->id, ['balance' => $wallet->balance]);
                    $this->customerTransactionRepository->delete($reversedTransaction->id);
                    return $branchReversalResult;
                }
            }
        }

        return responseData(true, Response::HTTP_OK, 'Transaction reversed successfully', $reversedTransaction);
    }

    public function reverseBranchTransaction(Transaction $originalTransaction): ResponseData
    {
        if ($originalTransaction->reversed_by) {
            return responseData(false, Response::HTTP_BAD_REQUEST, 'Transaction already reversed');
        }
        
        if ($originalTransaction->transaction_type === TransactionType::REVERSAL->value) {
            return responseData(false, Response::HTTP_BAD_REQUEST, 'Cannot reverse a reversal transaction');
        }

        // Create reversal transaction with original payment method
        $reversalData = $this->prepareReversalData($originalTransaction);
        
        $reversedTransaction = $this->transactionRepository->create($reversalData);
        
        if (!$reversedTransaction) {
            return responseData(false, Response::HTTP_INTERNAL_SERVER_ERROR, 'Failed to create reversal transaction');
        }

        // Update original transaction to point to this reversal
        $this->transactionRepository->update($originalTransaction->id, [
            'reversed_by' => $reversedTransaction->id
        ]);

        // Update branch wallet based on original payment method
        $wallet = $this->walletRepository->getByBranchId($originalTransaction->branch_id);
        
        $updateData = [];
        $amount = $originalTransaction->amount;
        
        if ($originalTransaction->type === Type::CREDIT->value) {
            // Original was credit (money came in), so reversal is debit (money goes out)
            if ($originalTransaction->payment_method === PaymentMethod::CASH->value) {
                $updateData['cash'] = $wallet->cash - $amount;
            } else {
                $updateData['bank'] = $wallet->bank - $amount;
            }
            $updateData['balance'] = $wallet->balance - $amount;
        } else {
            // Original was debit (money went out), so reversal is credit (money comes back)
            if ($originalTransaction->payment_method === PaymentMethod::CASH->value) {
                $updateData['cash'] = $wallet->cash + $amount;
            } else {
                $updateData['bank'] = $wallet->bank + $amount;
            }
            $updateData['balance'] = $wallet->balance + $amount;
        }
        
        if (!$this->walletRepository->update($wallet->id, $updateData)) {
            // Rollback if wallet update fails
            $this->transactionRepository->delete($reversedTransaction->id);
            return responseData(false, Response::HTTP_INTERNAL_SERVER_ERROR, 'Failed to update branch wallet');
        }

        return responseData(true, Response::HTTP_OK, 'Branch transaction reversed successfully', $reversedTransaction);
    }

    private function prepareReversalData($originalTransaction): array
    {
        // Calculate new balances
        $balanceAfter = $originalTransaction->type === Type::CREDIT->value 
            ? $originalTransaction->balance_after - $originalTransaction->amount 
            : $originalTransaction->balance_after + $originalTransaction->amount;

        return [
            'user_id' => Auth::id(),
            'branch_id' => $originalTransaction->branch_id,
            'customer_id' => $originalTransaction->customer_id,
            'payment_method' => $originalTransaction->payment_method, // Preserve original payment method
            'reference' => $this->generateReversalReference($originalTransaction->reference),
            'type' => $originalTransaction->type === Type::CREDIT->value ? Type::DEBIT->value : Type::CREDIT->value,
            'transaction_type' => TransactionType::REVERSAL->value,
            'amount' => $originalTransaction->amount,
            'balance_before' => $originalTransaction->balance_after,
            'balance_after' => $balanceAfter,
            'description' => 'Reversal of transaction ' . $originalTransaction->reference,
            'remark' => 'System generated reversal',
            'date' => now(),
            'reverses_id' => $originalTransaction->id
        ];
    }

    private function generateReversalReference(string $originalReference): string
    {
        return 'REV-' . $originalReference;
    }
}
