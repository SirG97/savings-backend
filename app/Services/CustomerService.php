<?php

namespace App\Services;

use App\Actions\ResponseData;
use App\Contracts\CustomerRepositoryInterface;
use App\Http\Requests\Customer\CustomerCreateRequest;
use App\Http\Requests\Customer\CustomerDeleteRequest;
use App\Http\Requests\Customer\CustomerUpdateRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CustomerService extends BasicCrudService
{

    public function __construct(private CustomerRepositoryInterface $customerRepository, private CustomerWalletService $customerWalletService)
    { }

    /**
     * Handle the create request.
     *
     * @param  \App\Http\Requests\Customer\CustomerCreateRequest $request
     * @return \App\Actions\ResponseData
     */
    public function handleCreate(CustomerCreateRequest $request): ResponseData
    {
        $validated = $request->validated();
        $validated['account_id'] = $this->generateAccountId();
        $validated['user_id'] = Auth::user()->id;
        $validated['branch_id'] = $validated['branch_id'] ?? Auth::user()->branch_id;

        $response =  $this->create($validated, $this->customerRepository);

        if(!$response->success){
            return $response;
        }

        $data = ['customer_id' => $response->data->id];
        //Create a wallet for the customer
        $walletResponse = $this->customerWalletService->handleCreate($data);

        if(!$walletResponse->success){
            $this->delete($data, $this->customerRepository);
            return responseData(false, Response::HTTP_INTERNAL_SERVER_ERROR,
                trans('crud.customer_wallet_failed_create'));
        }

        $customer = $this->customerRepository->getById($response->data->id);
        return responseData(true, Response::HTTP_OK,
            trans('crud.created'), $customer);

    }

    /**
     * Handle the update request.
     *
     * @param  \App\Http\Requests\Customer\CustomerUpdateRequest $request
     * @return \App\Actions\ResponseData
     */
    public function handleUpdate(CustomerUpdateRequest $request): ResponseData
    {

        return $this->update($request, $this->customerRepository);
    }

    /**
     * Handle the delete request.
     *
     * @param  \App\Http\Requests\Customer\CustomerDeleteRequest $request
     * @return \App\Actions\ResponseData
     */
    public function handleDelete(CustomerDeleteRequest $request): ResponseData
    {
        return $this->delete($request, $this->customerRepository);
    }

    /**
     * Handle the read request.
     *
     * @param null|string|int $id
     * @return array
     */
    public function handleRead(null|string|int $id = null): ResponseData
    {
        return $this->read($this->customerRepository, 'customer', $id);
    }


    /**
     * Handle the read by branch id request.
     *
     * @param null|string|int $id
     * @return array
     */
    public function handleReadByBranchId(int $branchId, null|string|int $id = null): ResponseData
    {

        return $this->readByBranchId($this->customerRepository, 'customer', $branchId, $id);
    }

    /**
     * Handle the read by branch id request.
     *
     * @param int $userId
     * @param null|string|int $id
     * @return ResponseData
     */
    public function handleReadByUserId(int $userId, null|string|int $id = null): ResponseData
    {

        return $this->readByUserId($this->customerRepository, 'customer', $userId, $id);
    }

    private function generateAccountId(): string
    {
        $digits_needed=8;

        $random_number=''; // set up a blank string

        $count=0;

        while ( $count < $digits_needed ) {
            $random_digit = mt_rand(0, 9);

            $random_number .= $random_digit;
            $count++;
        }

        $permitted_string = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $length = 2;
        $input_length = strlen($permitted_string);
        $random_string = '';
        for($i = 0; $i < $length; $i++) {
            $random_character = $permitted_string[mt_rand(0, $input_length - 1)];
            $random_string .= $random_character;
        }
        $account_id = $random_number . $random_string;

        if($customer = $this->customerRepository->getByAccountId($account_id)){
            $this->generateAccountId($length);
        }

        return $account_id;
    }

}
