<?php

namespace App\Services;

use App\Actions\ResponseData;
use App\Contracts\CustomerRepositoryInterface;
use App\Contracts\CustomerTransactionRepositoryInterface;
use App\Contracts\CustomerWalletRepositoryInterface;
use App\Enums\PaymentMethod;
use App\Enums\TransactionType;
use App\Enums\Type;
use App\Http\Requests\CustomerTransaction\CustomerTransactionCreateRequest;
use App\Http\Requests\CustomerTransaction\CustomerTransactionDeleteRequest;
use App\Http\Requests\CustomerTransaction\CustomerTransactionUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CustomerTransactionService extends BasicCrudService
{

    public function __construct(private CustomerTransactionRepositoryInterface $customerTransactionRepository,
                                private CustomerRepositoryInterface $customerRepository,
                                private CustomerWalletRepositoryInterface $customerWalletRepository,
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
        $validated['reference'] = $this->generateReference();
        $validated['date'] = $validated['date'] ?? now();
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
        return $this->readByTransactionType($this->customerTransactionRepository, 'user', $transactionType, $id);
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
            $this->generateReference($length);
        }

        return $random_string;
    }

}
