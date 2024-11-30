<?php

namespace App\Services;

use App\Actions\EmailData;
use App\Actions\ResponseData;
use App\Contracts\UserRepositoryInterface;
use App\Enums\UserModel;
use App\Enums\UserModelType;
use App\Events\EmailVerification;
use App\Http\Requests\User\UserAssignBranchRequest;
use App\Http\Requests\User\UserCreateRequest;
use App\Http\Requests\User\UserDeleteRequest;
use App\Http\Requests\User\UserSuspendRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Notifications\EmailNotification;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

//use App\Facades\ActivityLog;

class UserService extends BasicCrudService
{

    public function __construct(private UserRepositoryInterface $userRepository){

    }

    /**
     * Create User.
     *
     * @param UserCreateRequest $request
     * @return ResponseData
     */
    public function handleCreate(UserCreateRequest $request): ResponseData
    {
        $validated = $request->validated();
        $validated['model'] = UserModel::getType($validated['model']);
        $validated['name'] = $validated['first_name'] . ' ' . $validated['last_name'];
        $password = strtoupper(Str::random(8));
        $plainPassword = $password;
        $validated['password'] = Hash::make($password);
        $validated['default_password'] = '1';

        $response = $this->create($validated, $this->userRepository);
        if (!$response) {
            return responseData(false, Response::HTTP_BAD_REQUEST,
                trans('crud.failed_create'));
        }

        $title = trans('register.success');
        $text = trans('register.account');

        $url = (new $validated['model'])->url();
        $loggedInUser = Auth::user();
        $user = $this->userRepository->getByEmail($validated['email'])->first();

//        ActivityLog::create([
//            'log_name' => 'default',
//            'subject_type' => User::class,
//            'causer_id' => $loggedInUser->id,
//            'event' => 'created',
//            'properties' => ['values' => $user],
//            'causer_type' => $loggedInUser::class,
//            'description' => "{$loggedInUser->name} created a {$validated['model']} user with name {$validated['first_name']} {$validated['last_name']}({$user->unit_number})",
//
//        ]);

        $emailData = new EmailData(subject: $title, lines: [$text], action: true, action_text: trans('general.login'),
            action_url: $url, from: env('MAIL_FROM_ADDRESS'), remark: null, attachements: null, markdown: 'emails.notification',highlightText: $plainPassword  );
        $user->notify(new EmailNotification($emailData->toObject()));

        if (config('api.registration.notify.verify', true)) {
            EmailVerification::dispatch($user);
        }

        return responseData(true, Response::HTTP_OK, trans('register.success'));

    }

    /**
     * Update User.
     *
     * @param UserUpdateRequest|UserSuspendRequest $request
     * @return ResponseData
     */
    public function handleUpdate(UserUpdateRequest|UserSuspendRequest|UserAssignBranchRequest $request): ResponseData
    {
        $validated = $request->validated();
        $user = $this->userRepository->getById($validated['id']);
        if ($request instanceof UserSuspendRequest)
        {
            return $this->suspendUser($validated);
        }

        return $this->update($validated, $this->userRepository);
    }

    /**
     * Delete user .
     *
     * @param UserDeleteRequest $request
     * @return ResponseData
     */
    public function handleDelete(UserDeleteRequest $request): ResponseData
    {
        $validated = $request->validated();
        $loggedInUser = Auth::user();
        $user = $this->userRepository->getById($validated['id']);
        if ($user->id == $loggedInUser->id) {
            return responseData(false, Response::HTTP_BAD_REQUEST, trans('general.user.can_delete_self'));
        }
        return $this->delete($validated, $this->userRepository);
    }

    /**
     * Read user kyc.
     *
     * @param null|string|int $id
     * @return array
     */
    public function handleRead(null|string|int $id = null): ResponseData
    {
        return $this->read($this->userRepository, 'user', $id);
    }

    public function handleReadByUserModel(UserModelType $model, null|string|int $id = null): ResponseData
    {
        return $this->readByUserModel($this->userRepository, 'user', $model, $id);
    }

    private function suspendUser(array $validated): ResponseData
    {
        if ($validated['active'])
        {
            $title = trans('general.user.activated');
            $text = trans('general.user.activated_message');
            $user = $this->userRepository->getById($validated['id']);
            $url = (new $user->model)->url();
            $validated['suspended_at'] = NULL;

        } else {
            $title = trans('general.user.activated');
            $text = trans('general.user.activated_message');
            $user = $this->userRepository->getById($validated['id']);
            $url = (new $user->model)->url();
            $validated['suspended_at'] = now();
        }

        unset($validated['active']);
        $response = $this->update($validated, $this->userRepository);

        if ($response->success) {
            $emailData = new EmailData(subject: $title, lines: [$text], action: true, action_text: null,
                action_url: $url, from: env('MAIL_FROM_ADDRESS'), remark: null, attachements: null);

            $user->notify(new EmailNotification($emailData->toObject()));
        }

        return $response;
    }
}
