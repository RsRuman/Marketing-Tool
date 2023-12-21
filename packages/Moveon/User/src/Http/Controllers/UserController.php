<?php

namespace Moveon\User\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Moveon\User\Http\Requests\AssignRoleRequest;
use Moveon\User\Http\Requests\UserStatusUpdateRequest;
use Moveon\User\Http\Requests\UserStoreRequest;
use Moveon\User\Http\Requests\UserUpdateRequest;
use Moveon\User\Http\Resources\UserResource;
use Moveon\User\Services\UserService;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class UserController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * List of users
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function index(): JsonResponse
    {
        # Check authorization
        $this->authorize('view', User::class);

        $users = $this->userService->getUsers();
        $users = UserResource::collection($users);

        return Response::json($users, ResponseAlias::HTTP_OK);
    }

    /**
     * Create user
     * @param UserStoreRequest $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function create(UserStoreRequest $request): JsonResponse
    {
        # Check authorization
        $this->authorize('create', User::class);

        # Create user
        $user = $this->userService->createUser($request);

        if (!$user) {
            return Response::json([
                'error' => 'Could not create user. Please try again later.'
            ], ResponseAlias::HTTP_BAD_REQUEST);
        }

        $user = $user->load('roles');
        # Transform user
        $user = new UserResource($user);

        # Return response
        return Response::json($user, ResponseAlias::HTTP_CREATED);
    }

    /**
     * @throws AuthorizationException
     */
    public function update(UserUpdateRequest $request, $userId): JsonResponse
    {
        # Check authorization
        $this->authorize('update', User::class);

        # Update user
        $user = $this->userService->updateUser($request, $userId);

        if ($user === null) {
            # Return response
            return Response::json([
                'error' => 'User not found.'
            ], ResponseAlias::HTTP_NOT_FOUND);
        }

        if (!$user) {
            # Return response
            return Response::json([
                'error' => 'Could not update user. Please try later.'
            ], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = $user->load('roles');
        # Transform data
        $user = new UserResource($user);

        # Return response
        return Response::json($user, ResponseAlias::HTTP_OK);
    }

    /**
     * Show specific admin user
     * @throws AuthorizationException
     */
    public function show($id): JsonResponse
    {
        # Check authorize
        $this->authorize('view', User::class);

        # Get user
        $user = $this->userService->getUser($id);

        if (!$user) {
            # Return response
            return Response::json([
                'error' => 'User not found'
            ], ResponseAlias::HTTP_NOT_FOUND);
        }

        $user = $user->load('roles');

        # Transform data
        $user = new UserResource($user);

        # Return response
        return Response::json($user, ResponseAlias::HTTP_OK);
    }

    /**
     * Active inactive user
     * @param UserStatusUpdateRequest $request
     * @param $id
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function activeInactiveUser(UserStatusUpdateRequest $request, $id): JsonResponse
    {
        # Check authorization
        $this->authorize('inactiveUser', User::class);

        # Update status
        $user = $this->userService->changeStatus($request, $id);

        if (is_null($user)) {
            # Return response
            Response::json([
                'error' => 'User not found.'
            ], ResponseAlias::HTTP_NOT_FOUND);
        }

        if (!$user) {
            # Return response
            Response::json([
                'error' => 'Could not update status. Please try again later.'
            ], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }

        # Transform data
        $user = new UserResource($user);

        # Return response
        return Response::json($user, ResponseAlias::HTTP_OK);
    }

    /**
     * Assign role to user
     * @param AssignRoleRequest $request
     * @param $id
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function assignRoles(AssignRoleRequest $request, $id): JsonResponse
    {
        # Check authorize
        $this->authorize('assignRole', User::class);

        # Update role
        $user = $this->userService->updateRoles($request, $id);

        if (is_null($user)) {
            # Return response
            return Response::json([
                'error' => 'User not found.'
            ], ResponseAlias::HTTP_NOT_FOUND);
        }

        if (!$user) {
            # Return response
            return Response::json([
                'error' => 'Could assign role. Please try later.'
            ], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }

        # Transform data
        $user = new UserResource($user->fresh());

        # Return response
        return Response::json($user, ResponseAlias::HTTP_OK);
    }
}
