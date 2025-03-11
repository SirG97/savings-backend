<?php

namespace App\Services;

use App\Actions\ResponseData;
use App\Enums\TransactionType;
use App\Enums\UserModel;
use App\Enums\UserModelType;
use App\Facades\Database;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class BasicCrudService
{

    /**
     * Create record.
     *
     * @param mixed $request
     * @param mixed $repository
     * @return array
     */
    protected function create(mixed $request, mixed $repository): ResponseData
    {
        $validated = $this->getValidatedValues($request);

        if ($model = $repository->create($validated)) {
            return responseData(true, Response::HTTP_OK, trans('crud.created'),
                    $model);
        };

        return responseData(false, Response::HTTP_INTERNAL_SERVER_ERROR,
                    trans('crud.failed_create'));
    }

    /**
     * Update record.
     *
     * @param mixed $request
     * @param mixed $repository
     * @return array
     */
    protected function update(mixed $request, mixed $repository): ResponseData
    {
        $validated = $this->getValidatedValues($request);

        if (!$repository->getById($validated['id'])) {
            return responseData(false, Response::HTTP_INTERNAL_SERVER_ERROR,
                        trans('crud.failed_update'));
        }

        if ($repository->update($validated['id'], $validated) > 0)
        {
            return responseData(true, Response::HTTP_OK, trans('crud.updated'),
                        $repository->getById($validated['id']));
        }

        return responseData(false, Response::HTTP_INTERNAL_SERVER_ERROR,
                    trans('crud.failed_update'));
    }

    /**
     * Delete record.
     *
     * @param mixed $request
     * @param mixed $repository
     * @return array
     */
    protected function delete(mixed $request, mixed $repository): ResponseData
    {
        $validated = $this->getValidatedValues($request);

        if ($repository->getById($validated['id'])) {
            $repository->delete($validated['id']);

            return responseData(true, Response::HTTP_OK,
                        trans('crud.deleted'));
        }

        return responseData(false, Response::HTTP_INTERNAL_SERVER_ERROR,
                    trans('crud.failed_delete'));
    }

    /**
     * Read records.
     *
     * @param mixed $repository
     * @param null|string|int $id
     * @return array
     */
    protected function read(mixed $repository, string $index, null|string|int $id = null): ResponseData
    {
        if (!isset($id)) {
            return responseData(true, Response::HTTP_OK, trans('crud.read'),
                    $repository->getPaginated(config("api.paginate.{$index}.pageSize")));
        }

        if ($id === 'all') {
            return responseData(true, Response::HTTP_OK, trans('crud.read'),
                    $repository->getAll());
        }

        return responseData(true, Response::HTTP_OK, trans('crud.read'),
                    $repository->getById($id));
    }

    /**
     * Read records.
     *
     * @param mixed $repository
     * @param null|string|int $id
     * @return array
     */
    protected function readByUserId(mixed $repository, string $index, string|int $userId, null|string|int $id = null): ResponseData
    {
        if (!isset($id)) {
            return responseData(true, Response::HTTP_OK, trans('crud.read'),
                    $repository->getByUserIdPaginated($userId, config("api.paginate.{$index}.pageSize")));
        }

        if ($id === 'all') {
            return responseData(true, Response::HTTP_OK, trans('crud.read'),
                    $repository->getByUserId($userId));
        }

        return responseData(true, Response::HTTP_OK, trans('crud.read'),
                    $repository->getByUserIdAndId($userId, $id));
    }

    /**
     * Read records.
     *
     * @param mixed $repository
     * @param null|string|int $id
     * @return array
     */
    protected function readByCustomerId(mixed $repository, string $index, string|int $customerId, null|string|int $id = null): ResponseData
    {
        if (!isset($id)) {
            return responseData(true, Response::HTTP_OK, trans('crud.read'),
                $repository->getByCustomerIdPaginated($customerId, config("api.paginate.{$index}.pageSize")));
        }

        if ($id === 'all') {
            return responseData(true, Response::HTTP_OK, trans('crud.read'),
                $repository->getByCustomerId($customerId));
        }

        return responseData(true, Response::HTTP_OK, trans('crud.read'),
            $repository->getById($customerId, $id));
    }

    /**
     * Read records.
     *
     * @param mixed $repository
     * @param null|string|int $id
     * @return array
     */
    protected function readByUserModel(mixed $repository, string $index, UserModelType $model, null|string|int $id = null): ResponseData
    {

        if (!isset($id)) {
            return responseData(true, Response::HTTP_OK, trans('crud.read'),
                $repository->getByUserModelPaginated($model, config("api.paginate.{$index}.pageSize")));
        }

        if ($id === 'all') {
            return responseData(true, Response::HTTP_OK, trans('crud.read'),
                $repository->getByUserModel($model));
        }

        return responseData(true, Response::HTTP_OK, trans('crud.read'),
            $repository->getByUserModel($model));
    }

    protected function readByTransactionType(mixed $repository, string $index, TransactionType $transactionType, null|string|int $id = null): ResponseData
    {
        if (!isset($id)) {
            return responseData(true, Response::HTTP_OK, trans('crud.read'),
                $repository->getByTransactionTypePaginated($transactionType, config("api.paginate.{$index}.pageSize")));
        }

        if ($id === 'all') {
            return responseData(true, Response::HTTP_OK, trans('crud.read'),
                $repository->getByTransactionType($transactionType));
        }

        return responseData(true, Response::HTTP_OK, trans('crud.read'),
            $repository->getByTransactionTypeAndId($transactionType, $id));
    }

    protected function readByBranchId(mixed $repository, string $index, int $branchId, null|string|int $id = null): ResponseData
    {

        if (!isset($id)) {
            return responseData(true, Response::HTTP_OK, trans('crud.read'),
                $repository->getByBranchIdPaginated($branchId, config("api.paginate.{$index}.pageSize")));
        }

        if ($id === 'all') {
            return responseData(true, Response::HTTP_OK, trans('crud.read'),
                $repository->getByBranchId($branchId));
        }

        return responseData(true, Response::HTTP_OK, trans('crud.read'),
            $repository->getByBranchIdAndId($branchId, $id));
    }

    protected function readByTransactionTypeAndBranchId(mixed $repository, string $index, TransactionType $transactionType, int $branchId, null|string|int $id = null): ResponseData
    {

        if (!isset($id)) {
            return responseData(true, Response::HTTP_OK, trans('crud.read'),
                $repository->getByTransactionTypeAndBranchIdPaginated($transactionType, $branchId, config("api.paginate.{$index}.pageSize")));
        }

        if ($id === 'all') {
            return responseData(true, Response::HTTP_OK, trans('crud.read'),
                $repository->getByTransactionTypeAndBranchId($transactionType, $branchId));
        }

        return responseData(true, Response::HTTP_OK, trans('crud.read'),
            $repository->getByBranchIdAndId($branchId, $id));
    }

    protected function readByTransactionTypeAndUserId(mixed $repository, string $index, TransactionType $transactionType, int $userId, null|string|int $id = null): ResponseData
    {
   
        if (!isset($id)) {
            return responseData(true, Response::HTTP_OK, trans('crud.read'),
                $repository->getByTransactionTypeAndUserIdPaginated($transactionType, $userId, config("api.paginate.{$index}.pageSize")));
        }

        if ($id === 'all') {
            return responseData(true, Response::HTTP_OK, trans('crud.read'),
                $repository->getByTransactionTypeAndUserId($transactionType, $userId));
        }

        return responseData(true, Response::HTTP_OK, trans('crud.read'),
            $repository->getByUserIdAndId($userId, $id));
    }


    protected function readByTransactionTypeAndCustomerId(mixed $repository, string $index, TransactionType $transactionType, int $customerId, null|string|int $id = null): ResponseData
    {
   
        if (!isset($id)) {
            return responseData(true, Response::HTTP_OK, trans('crud.read'),
                $repository->getByTransactionTypeAndCustomerIdPaginated($transactionType, $customerId, config("api.paginate.{$index}.pageSize")));
        }

        if ($id === 'all') {
            return responseData(true, Response::HTTP_OK, trans('crud.read'),
                $repository->getByTransactionTypeAndCustomerId($transactionType, $customerId));
        }

        return responseData(true, Response::HTTP_OK, trans('crud.read'),
            $repository->getById($customerId, $id));
    }

    /**
     * Create record with DB facade.
     *
     * @param mixed $request
     * @param string $table
     * @return array
     */
    protected function createWithDB(mixed $request, string $table): ResponseData
    {
        $validated = $this->getValidatedValues($request);

        if ($id = Database::create($validated, $table)) {
            $model = Database::getById($id, $table);
            return responseData(true, Response::HTTP_OK, trans('crud.created'), $model);
        };

        return responseData(false, Response::HTTP_INTERNAL_SERVER_ERROR,
                    trans('crud.failed_create'));
    }

    /**
     * Update record with DB facade.
     *
     * @param mixed $request
     * @param string $table
     * @return array
     */
    protected function updateWithDB(mixed $request, string $table): ResponseData
    {
        $validated = $this->getValidatedValues($request);

        if (!Database::getById($validated['id'], $table)) {
            return responseData(false, Response::HTTP_INTERNAL_SERVER_ERROR,
                        trans('crud.failed_update'));
        }

        if (Database::update($validated['id'], $validated, $table) > 0)
        {
            $model = Database::getById($validated['id'], $table);
            return responseData(true, Response::HTTP_OK, trans('crud.updated'),$model);
        }

        return responseData(false, Response::HTTP_INTERNAL_SERVER_ERROR,
                    trans('crud.failed_update'));
    }

    /**
     * @param mixed $request
     * @return array
     */
    protected function getValidatedValues(mixed $request): array
    {
        if (is_array($request)) {
            $validated = $request;
        } else {
            $validated = $request->validated();
        }

        return $validated;
    }


}
