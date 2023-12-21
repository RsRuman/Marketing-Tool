<?php

namespace Moveon\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Moveon\User\Events\CreateAdminRoleEvent;
use Moveon\User\Events\FilterSeedEvent;
use Moveon\User\Events\SignUpUserUpdateEvent;
use Moveon\User\Http\Requests\SignInRequest;
use Moveon\User\Http\Requests\UserStoreRequest;
use Moveon\User\Http\Resources\UserResource;
use Moveon\User\Services\UserService;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class AuthController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * User registration
     * @param UserStoreRequest $request
     * @return JsonResponse
     */
    public function signUp(UserStoreRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            # Create user
            $user = $this->userService->createUser($request);

            if (!$user) {
                throw new \Exception('Could not signup. Please try again later.');
            }

            $data = [
                'name'       => 'admin',
                'guard_name' => 'api',
                'user_id'    => $user->id
            ];

            # Create Role for newly sign up user
            $role = Role::query()->create($data);

            if (!$role) {
                throw new \Exception('Could not signup. Please try again later.');
            }

            $user->assignRole($role);

            DB::commit();

            # Assign permissions to this user role
            event(new CreateAdminRoleEvent($user));
            # Update user
            event(new SignUpUserUpdateEvent($user));
            # Filter and criteria seeding to this user
            event(new FilterSeedEvent($user));

            $user->load('roles');

            # Transform user
            $user = new UserResource($user);

            # Return response
            return Response::json($user, ResponseAlias::HTTP_CREATED);
        } catch (\Exception $ex) {
            DB::rollBack();
            return Response::json([
                'error' => $ex->getMessage()
            ], ResponseAlias::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Sign in user
     * @param SignInRequest $request
     * @return JsonResponse
     */
    public function signIn(SignInRequest $request): JsonResponse
    {
        $token = $this->userService->getToken($request);

        if (!$token) {
            return Response::json([
                'error' => 'Provided credential is invalid'
            ], ResponseAlias::HTTP_NOT_FOUND);
        }

        # Return response
        return Response::json([
            'token' => $token
        ], ResponseAlias::HTTP_OK);
    }

    /**
     * User sign out
     * @return JsonResponse
     */
    public function signOut(): JsonResponse
    {
        $user = Auth::user();

        $user->tokens->each(function ($token) {
            $token->revoke();
        });

        return Response::json([
            'message' => 'Sign out successful.'
        ], ResponseAlias::HTTP_OK);
    }
}
