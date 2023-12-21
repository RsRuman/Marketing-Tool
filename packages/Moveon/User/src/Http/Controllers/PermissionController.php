<?php

namespace Moveon\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Moveon\User\Http\Resources\PermissionResource;
use Moveon\User\Services\PermissionService;
use Spatie\Permission\Models\Permission;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class PermissionController extends Controller
{
    private PermissionService $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    /**
     * Permission list
     * @throws AuthorizationException
     */
    public function index(): JsonResponse
    {
        # Check authorization
        $this->authorize('view', Permission::class);

        # Get Permissions
        $permissions = $this->permissionService->getPermissions();

        # Transform data
        $permissions = PermissionResource::collection($permissions);

        # Return response
        return Response::json($permissions, ResponseAlias::HTTP_OK);
    }
}
