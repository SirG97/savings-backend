<?php

namespace App\Services;

use App\Actions\ResponseData;
use App\Contracts\LoanRepositoryInterface;
use App\Http\Requests\Loan\LoanCreateRequest;
use App\Http\Requests\Loan\LoanDeleteRequest;
use App\Http\Requests\Loan\LoanUpdateRequest;

class LoanService extends BasicCrudService
{

    public function __construct(private LoanRepositoryInterface $loanRepository)
    { }

    /**
     * Handle the create request.
     *
     * @param  \App\Http\Requests\Loan\LoanCreateRequest $request
     * @return \App\Actions\ResponseData
     */
    public function handleCreate(LoanCreateRequest $request): ResponseData
    {
        return $this->create($request, $this->loanRepository);
    }

    /**
     * Handle the update request.
     *
     * @param  \App\Http\Requests\Loan\LoanUpdateRequest $request
     * @return \App\Actions\ResponseData
     */
    public function handleUpdate(LoanUpdateRequest $request): ResponseData
    {
        return $this->update($request, $this->loanRepository);
    }

    /**
     * Handle the delete request.
     *
     * @param  \App\Http\Requests\Loan\LoanDeleteRequest $request
     * @return \App\Actions\ResponseData
     */
    public function handleDelete(LoanDeleteRequest $request): ResponseData
    {
        return $this->delete($request, $this->loanRepository);
    }

    /**
     * Handle the read request.
     *
     * @param null|string|int $id
     * @return array
     */
    public function handleRead(null|string|int $id = null): ResponseData
    {
        return $this->read($this->loanRepository, 'loan', $id);
    }

}
