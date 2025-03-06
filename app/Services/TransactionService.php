<?php

namespace App\Services;

use App\Actions\ResponseData;
use App\Contracts\CustomerRepositoryInterface;
use App\Contracts\TransactionRepositoryInterface;
use App\Contracts\WalletRepositoryInterface;
use App\Enums\PaymentMethod;
use App\Enums\TransactionType;
use App\Enums\Type;
use App\Http\Requests\CustomerTransaction\CustomerTransactionCreateRequest;
use App\Http\Requests\Transaction\TransactionCreateRequest;
use App\Http\Requests\Transaction\TransactionDeleteRequest;
use App\Http\Requests\Transaction\TransactionUpdateRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class TransactionService extends BasicCrudService
{

    public function __construct(private TransactionRepositoryInterface $transactionRepository,
                                private CustomerRepositoryInterface $customerRepository,
                                private WalletRepositoryInterface $walletRepository)
    { }

    /**
     * Handle the create request.
     *
     * @param TransactionCreateRequest|array $request
     * @return ResponseData
     */
    public function handleCreate(TransactionCreateRequest|CustomerTransactionCreateRequest $request): ResponseData
    {

        $validated = $request->validated();
        $validated['user_id'] = Auth::user()->id;
        $customer = $this->customerRepository->getById($validated['customer_id']);

        
        $validated['branch_id'] = $customer->branch_id;
        $wallet = $this->walletRepository->getByBranchId($validated['branch_id']);
        $validated['reference'] = $this->generateReference();
        $validated['date'] = $validated['date'] ?? now();
        $validated['balance_before'] = $wallet->balance;

        if($validated['transaction_type'] === TransactionType::DEPOSIT->value or
            $validated['transaction_type'] === TransactionType::COMMISSION->value) {

            $validated['type'] = Type::CREDIT->value;
            $balance = $wallet->balance + (float)$validated['amount'];
            $validated['balance_after'] = $balance;

            //Update wallet
            if ($validated['payment_method'] === PaymentMethod::CASH->value) {
                $walletData = [
                    'balance' => $balance,
                    'cash' => $wallet->cash + (float)$validated['amount'],
                ];

            } else {
                $walletData = [
                    'balance' => $balance,
                    'bank' => $wallet->cash + (float)$validated['amount'],
                ];
            }
        }elseif($validated['transaction_type'] === TransactionType::TRANSFER->value){
            $validated['type'] = Type::DEBIT->value;

        }else{
            $validated['type'] = Type::DEBIT->value;
            $balance = $wallet->balance - (float)$validated['amount'];
            $validated['balance_after'] = $balance;

            if($validated['payment_method'] === PaymentMethod::CASH->value and
                $wallet->cash < (float)$validated['amount']){
                return responseData(false, Response::HTTP_BAD_REQUEST,
                    'Insufficient cash balance in the branch');
            }

            if($validated['payment_method'] === PaymentMethod::BANK->value and
                $wallet->bank < (float)$validated['amount']){
                return responseData(false, Response::HTTP_BAD_REQUEST,
                    'Insufficient bank balance in the branch');
            }

            //Update wallet
            if($validated['payment_method'] === PaymentMethod::CASH->value){
                $walletData = [
                    'balance' => $balance,
                    'cash' => $wallet->cash - (float)$validated['amount'],
                ];

            }else{
                $walletData = [
                    'balance' => $balance,
                    'bank' => $wallet->cash - (float)$validated['amount'],
                ];
            }

        }

        $response = $this->create($validated, $this->transactionRepository);

        if(!$response->success){
            $this->delete(['id' => $response->data->id], $this->transactionRepository);
            return responseData(false, Response::HTTP_INTERNAL_SERVER_ERROR,
                'Failed to create transaction');
        }

        if(!$this->walletRepository->update($wallet->id, $walletData)){
            $this->delete(['id' => $response->data->id], $this->transactionRepository);
            return responseData(false, Response::HTTP_INTERNAL_SERVER_ERROR,
                'Branch Wallet could not be updated');
        }

        return $response;
    }

    /**
     * Handle the update request.
     *
     * @param  \App\Http\Requests\Transaction\TransactionUpdateRequest $request
     * @return \App\Actions\ResponseData
     */
    public function handleUpdate(TransactionUpdateRequest $request): ResponseData
    {
        return $this->update($request, $this->transactionRepository);
    }

    /**
     * Handle the delete request.
     *
     * @param  \App\Http\Requests\Transaction\TransactionDeleteRequest $request
     * @return \App\Actions\ResponseData
     */
    public function handleDelete(TransactionDeleteRequest $request): ResponseData
    {
        return $this->delete($request, $this->transactionRepository);
    }

    /**
     * Handle the read request.
     *
     * @param null|string|int $id
     * @return array
     */
    public function handleRead(null|string|int $id = null): ResponseData
    {
        return $this->read($this->transactionRepository, 'transaction', $id);
    }

    public function handleReadByTransactionType(TransactionType $transactionType, null|string|int $id = null): ResponseData
    {
        return $this->readByTransactionType($this->transactionRepository, 'transaction', $transactionType, $id);
    }


    public function handleReadByTransactionTypeAndBranchId(TransactionType $transactionType, int $branchId, null|string|int $id = null): ResponseData
    {
        return $this->readByTransactionTypeAndBranchId($this->transactionRepository, 'transaction', $transactionType, $branchId, $id);
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


        if($reference = $this->transactionRepository->getByReference($random_string)){
            $this->generateReference($length);
        }

        return $random_string;
    }



}
