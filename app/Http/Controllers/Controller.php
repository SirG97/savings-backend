<?php

namespace App\Http\Controllers;

use App\Enums\UserModelType;
use App\Traits\Helpers;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function _create(mixed $request, mixed $service): JsonResponse
    {
        if ($data = $service->handleCreate($request)) {
            return httpJsonResponse($data);
        };

        return unknownErrorJsonResponse();
    }

    protected function _update(mixed $request, mixed $service): JsonResponse
    {
        if ($data = $service->handleUpdate($request)) {
            return httpJsonResponse($data);
        };

        return unknownErrorJsonResponse();
    }

    protected function _delete(mixed $request, mixed $service): JsonResponse
    {
        if ($data = $service->handleDelete($request)) {
            return httpJsonResponse($data);
        };

        return unknownErrorJsonResponse();
    }

    protected function _read(mixed $service, null|string|int $id = null): JsonResponse
    {
        if ($data = $service->handleRead($id)) {
            return httpJsonResponse($data);
        };

        return unknownErrorJsonResponse();
    }

    protected function _readByUserId(mixed $service, int $userId, null|string|int $id = null): JsonResponse
    {
        if ($data = $service->handleReadByUserId($userId, $id)) {
            return httpJsonResponse($data);
        };

        return unknownErrorJsonResponse();
    }

    protected function _readByUserModel(mixed $service, UserModelType $model, null|string|int $id = null): JsonResponse
    {
        if ($data = $service->handleReadByUserModel($model, $id)) {
            return httpJsonResponse($data);
        };

        return unknownErrorJsonResponse();
    }
}
