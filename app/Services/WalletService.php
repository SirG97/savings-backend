<?php

namespace App\Services;

use App\Actions\ResponseData;
use App\Contracts\WalletRepositoryInterface;
use App\Http\Requests\WalletCreateRequest;
use App\Http\Requests\WalletDeleteRequest;
use App\Http\Requests\WalletUpdateRequest;

class WalletService extends BasicCrudService
{

    public function __construct(private WalletRepositoryInterface $walletRepository)
    { }

    /**
     * Handle the create request.
     *
     * @param array $validated
     * @return ResponseData
     */
    public function handleCreate(array $validated): ResponseData
    {
        return $this->create($validated, $this->walletRepository);
    }

    /**
     * Handle the update request.
     *
     * @param  \App\Http\Requests\WalletUpdateRequest $request
     * @return \App\Actions\ResponseData
     */
    public function handleUpdate(WalletUpdateRequest $request): ResponseData
    {
        return $this->update($request, $this->walletRepository);
    }

    /**
     * Handle the delete request.
     *
     * @param  \App\Http\Requests\WalletDeleteRequest $request
     * @return \App\Actions\ResponseData
     */
    public function handleDelete(WalletDeleteRequest $request): ResponseData
    {
        return $this->delete($request, $this->walletRepository);
    }

    /**
     * Handle the read request.
     *
     * @param null|string|int $id
     * @return array
     */
    public function handleRead(null|string|int $id = null): ResponseData
    {
        return $this->read($this->walletRepository, 'wallet', $id);
    }

}
