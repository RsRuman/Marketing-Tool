<?php

namespace Moveon\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Moveon\User\Http\Requests\AssignPermissionRequest;
use Moveon\User\Http\Requests\RoleStoreRequest;
use Moveon\User\Http\Requests\RoleUpdateRequest;
use Moveon\User\Http\Resources\RoleResource;
use Moveon\User\Services\RoleService;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class RoleController extends Controller
{
    private RoleService $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    /**
     * Role list
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function index(): JsonResponse
    {
        # Check authorization
        $this->authorize('view', Role::class);

        $roles = $this->roleService->getRoles();

        $roles = RoleResource::collection($roles);

        return Response::json($roles, ResponseAlias::HTTP_OK);
    }

    /**
     * Create role
     * @param RoleStoreRequest $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function create(RoleStoreRequest $request): JsonResponse
    {
        # Check authorization
        $this->authorize('create', Role::class);

        try {
            DB::beginTransaction();
            # Create role
            $role = $this->roleService->createRole($request);

            if (!$role) {
                throw new Exception('Could not create role. Please try later.');
            }

            DB::commit();

            # Transform role
            $role = new RoleResource($role);

            return Response::json($role, ResponseAlias::HTTP_CREATED);
        } catch (\Exception $ex) {
            DB::rollBack();
            return Response::json([
                'error' => $ex->getMessage()
            ], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Update role
     * @throws AuthorizationException
     */
    public function update(RoleUpdateRequest $request, $id): JsonResponse
    {
        # Check authorization
        $this->authorize('update', Role::class);

        $role = $this->roleService->updateRole($request, $id);

        if (is_null($role)) {
            Response::json([
               'error' => 'Role not found.'
            ], ResponseAlias::HTTP_NOT_FOUND);
        }

        if (!$role) {
            Response::json([
                'error' => 'Role is not update. Please try later.'
            ], ResponseAlias::HTTP_NOT_FOUND);
        }

        # Transform role
        $role = new RoleResource($role);

        return Response::json($role, ResponseAlias::HTTP_OK);
    }

    /**
     * Delete role
     * @throws AuthorizationException
     */
    public function destroy($id): JsonResponse
    {
        $this->authorize('delete', Role::class);

        $role = $this->roleService->deleteRole($id);

        if (is_null($role)) {
            return Response::json([
                'error' => 'Role not found.'
            ], ResponseAlias::HTTP_NOT_FOUND);
        }

        if (!$role) {
            return Response::json([
                'error' => 'Could not delete role. Please try again later.'
            ], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }

        return Response::json([
            'message' => 'Role deleted successfully.'
        ], ResponseAlias::HTTP_OK);
    }

    /**
     * Assign permissions to role
     * @throws AuthorizationException
     */
    public function assignPermissionToRole(AssignPermissionRequest $request, $id): JsonResponse
    {
        # Check authorization
        $this->authorize('assignPermissionToRole', Role::class);

        if (auth()->user()->id === (int) $id) {
            return Response::json([
                'error' => 'You can not update your own role.'
            ], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }

        $syncPermission = $this->roleService->updateRolePermissions($request, $id);

        if (is_null($syncPermission)) {
            return Response::json([
                'error' => 'Role not found'
            ], ResponseAlias::HTTP_NOT_FOUND);
        }

        if (!$syncPermission) {
            return Response::json([
                'error' => 'Permissions are not update. Please try later.'
            ], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }

        # Transform data
        $role = new RoleResource($syncPermission);

        return Response::json( $role,ResponseAlias::HTTP_OK);
    }

    /**
     * @throws AuthorizationException
     */
    public function show($id) {
        # Check authorization
        $this->authorize('view', Role::class);

        $role = $this->roleService->getRole($id);

        if (!$role) {
            return Response::json([
                'error' => 'Role note found!'
            ], ResponseAlias::HTTP_NOT_FOUND);
        }

        # Transform data
        $role = new RoleResource($role);

        return Response::json($role, ResponseAlias::HTTP_OK);
    }
}
