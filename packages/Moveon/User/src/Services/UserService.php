<?php

namespace Moveon\User\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Moveon\User\Repositories\UserRepository;

class UserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Get all user
     * @return Collection
     */
    public function getUsers(): Collection
    {
        return $this->userRepository->all();
    }

    /**
     * Create user
     * @param $request
     * @return mixed
     */
    public function createUser($request): mixed
    {
        # Sanitize data
        $hasPassword = Hash::make($request->input('password'));
        $data = $request->safe()->only('first_name', 'last_name', 'user_name', 'email');
        $data['password']  = $hasPassword;
        $data['origin_id'] = $request->user() ? $request->user()->id : null;

        # Create user
        return $this->userRepository->create($data);
    }

    /**
     * Get token
     * @param $request
     * @return false
     */
    public function getToken($request):mixed {
        # Sanitize data
        $data = $request->safe()->only('email', 'password');

        # Return token
        return $this->userRepository->token($data);

    }

    /**
     * Update user
     * @param $request
     * @param $userId
     * @return Model|Collection|bool|Builder|array|null
     */
    public function updateUser($request, $userId): Model|Collection|bool|Builder|array|null
    {
        # Find user
        $user = $this->userRepository->find($userId);

        if (!$user) {
            return null;
        }
        # Sanitize data
        $hasPassword = Hash::make($request->input('password'));
        $data = $request->safe()->only('first_name', 'last_name', 'user_name', 'email');
        $data['password'] = $hasPassword;

        # Update user
        $userU = $this->userRepository->update($data, $user);

        if ($userU) {
            return $user->fresh();
        }

        return false;
    }

    /**
     * Get single admin user
     * @param $id
     * @return Model|Collection|Builder|array|null
     */
    public function getUser($id): Model|Collection|Builder|array|null
    {
        return $this->userRepository->find($id);
    }

    /**
     * Change user status
     * @param $request
     * @param $id
     * @return mixed
     */
    public function changeStatus($request, $id): mixed
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            return null;
        }

        $data = $request->safe()->only('status');

        $userU = $this->userRepository->update($data, $user);

        if (!$userU) {
            return false;
        }

        return $user->fresh();
    }

    /**
     * Update roles
     * @param $request
     * @param $id
     * @return mixed|null
     */
    public function updateRoles($request, $id): mixed
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            return null;
        }

        $data = $request->safe()->only('roleIds');

        return $this->userRepository->syncRoles($data, $user);
    }
}
