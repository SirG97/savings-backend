<?php

namespace App\Services;

use App\Actions\ResponseData;
use App\Contracts\CustomerRepositoryInterface;
use App\Contracts\CustomerTransactionRepositoryInterface;
use App\Contracts\TransactionRepositoryInterface;
use App\Contracts\UserRepositoryInterface;
use App\Models\Admin;
use App\Models\Marketer;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class SearchService
{

    public function __construct(private CustomerRepositoryInterface $customerRepository,
        private CustomerTransactionRepositoryInterface $customerTransactionRepository,
        private TransactionRepositoryInterface $transactionRepository,
        private UserRepositoryInterface $userRepository)
    { }

    /**
     * Customer search function
     *
     * @param string $value
     * @return null|array
     */
    public function handleCustomerSearch(string $value): ResponseData
    {
        $user = Auth::user();

        if($user->model === Marketer::class){
            $data = $this->customerRepository->search($value,$user->branch_id, $user->id);

//            return responseData(true, Response::HTTP_OK, trans('crud.read'));
        }else if($user->model === Admin::class){
            $data = $this->customerRepository->search($value, $user->branch_id);
        }else{
            $data = $this->customerRepository->search($value);
        }

        return responseData(true, Response::HTTP_OK, trans('crud.read'), $data);
    }

    /**
     * User search function
     *
     * @param string $value
     * @return null|array
     */
    public function handleUserSearch(string $value): ResponseData
    {
        $data = $this->userRepository->search($value);

        return responseData(true, Response::HTTP_OK, trans('crud.read'), $data);
    }

    /**
     * CustomerTransaction search function
     *
     * @param string $value
     * @return null|array
     */
    public function handleCustomerTransactionSearch(string $value): ResponseData
    {
        $data = $this->customerTransactionRepository->search($value);

        return responseData(true, Response::HTTP_OK, trans('crud.read'), $data);
    }

    /**
     * CustomerTransaction search function
     *
     * @param string $value
     * @return null|array
     */
    public function handleTransactionSearch(string $value): ResponseData
    {
        $data = $this->transactionRepository->search($value);

        return responseData(true, Response::HTTP_OK, trans('crud.read'), $data);
    }

}
