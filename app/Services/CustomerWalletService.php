<?php

namespace App\Services;

use App\Actions\ResponseData;
use App\Contracts\CustomerWalletRepositoryInterface;


class CustomerWalletService extends BasicCrudService
{

    public function __construct(private CustomerWalletRepositoryInterface $customerWalletRepository)
    { }

    /**
     * Handle the create request.
     *
     * @param array $request
     * @return ResponseData
     */
    public function handleCreate(array $request): ResponseData
    {
        return $this->create($request, $this->customerWalletRepository);
    }

    /**
     * Handle the update request.
     *
     * @param mixed $request
     * @return ResponseData
     */
    public function handleUpdate(mixed $request): ResponseData
    {
        return $this->update($request, $this->customerWalletRepository);
    }

    /**
     * Handle the read request.
     *
     * @param null|string|int $id
     * @return ResponseData
     */
    public function handleRead(null|string|int $id = null): ResponseData
    {
        return $this->read($this->customerWalletRepository, 'customer_wallet', $id);
    }

}
