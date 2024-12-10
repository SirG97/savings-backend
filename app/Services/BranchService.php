<?php

namespace App\Services;

use App\Actions\ResponseData;
use App\Contracts\BranchRepositoryInterface;
use App\Http\Requests\Branch\BranchCreateRequest;
use App\Http\Requests\Branch\BranchDeleteRequest;
use App\Http\Requests\Branch\BranchUpdateRequest;
use Illuminate\Http\Response;

class BranchService extends BasicCrudService
{

    public function __construct(private BranchRepositoryInterface $branchRepository,
                                private WalletService $walletService)
    { }

    /**
     * Handle the create request.
     *
     * @param  \App\Http\Requests\Branch\BranchCreateRequest $request
     * @return \App\Actions\ResponseData
     */
    public function handleCreate(BranchCreateRequest $request): ResponseData
    {
        $response =  $this->create($request, $this->branchRepository);

        if(!$response->success){
            return $response;
        }
        $data = ['branch_id' => $response->data->id];
        $walletResponse = $this->walletService->handleCreate($data);

        if(!$walletResponse->success){
            $this->delete($data, $this->branchRepository);
            return responseData(false, Response::HTTP_INTERNAL_SERVER_ERROR,
                trans('crud.branch_wallet_failed_create'));
        }

        $branch = $this->branchRepository->getById($response->data->id);

        return responseData(true, Response::HTTP_OK,
            trans('crud.created'), $branch);

    }

    /**
     * Handle the update request.
     *
     * @param  \App\Http\Requests\Branch\BranchUpdateRequest $request
     * @return \App\Actions\ResponseData
     */
    public function handleUpdate(BranchUpdateRequest $request): ResponseData
    {
        return $this->update($request, $this->branchRepository);
    }

    /**
     * Handle the delete request.
     *
     * @param  \App\Http\Requests\Branch\BranchDeleteRequest $request
     * @return \App\Actions\ResponseData
     */
    public function handleDelete(BranchDeleteRequest $request): ResponseData
    {
        return $this->delete($request, $this->branchRepository);
    }

    /**
     * Handle the read request.
     *
     * @param null|string|int $id
     * @return array
     */
    public function handleRead(null|string|int $id = null): ResponseData
    {
        return $this->read($this->branchRepository, 'branch', $id);
    }

}
