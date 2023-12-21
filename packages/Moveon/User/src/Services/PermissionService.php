<?php

namespace Moveon\User\Services;

use Illuminate\Database\Eloquent\Collection;
use Moveon\User\Repositories\PermissionRepository;

class PermissionService
{
    private PermissionRepository $permissionRepository;

    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * Get all permissions
     * @return Collection
     */
    public function getPermissions(): Collection
    {
        return $this->permissionRepository->all();
    }
}
