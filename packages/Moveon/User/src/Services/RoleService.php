<?php

namespace Moveon\User\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Moveon\User\Repositories\RoleRepository;

class RoleService
{
    private RoleRepository $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    /**
     * Get roles
     * @return Collection|array
     */
    public function getRoles(): Collection|array
    {
        return $this->roleRepository->all();
    }

    /**
     * Create role
     * @param $request
     * @return Model|Builder
     */
    public function createRole($request): Model|Builder
    {
        # Sanitize data
        $data = $request->safe()->only('name');
        $data['name'] = Str::lower($data['name']);
        $data['guard_name'] = 'api';
        $data['user_id']    = auth()->user()->id;

        return $this->roleRepository->create($data);

    }

    /**
     * Update role
     * @param $request
     * @param $id
     * @return array|Builder|Collection|Model|bool|null
     */
    public function updateRole($request, $id): array|Builder|Collection|Model|null|bool
    {
        $role = $this->roleRepository->find($id);

        if (!$role) {
            return null;
        }

        # Sanitize data
        $data = $request->safe()->only('name');
        $data['name'] = Str::lower($data['name']);
        $data['guard_name'] = 'api';

        $roleU = $this->roleRepository->update($data, $id);

        if (!$roleU) {
            return false;
        }

        return $role->fresh();
    }

    /**
     * Delete role
     * @param $id
     * @return mixed|null
     */
    public function deleteRole($id): mixed
    {
        $role = $this->roleRepository->find($id);

        if (!$role) {
            return null;
        }

        return $this->roleRepository->delete($role);
    }

    /**
     * Role permissions update
     * @param $request
     * @param $id
     * @return Model|Collection|bool|Builder|array|null
     */
    public function updateRolePermissions($request, $id): Model|Collection|bool|Builder|array|null
    {
        $role = $this->roleRepository->find($id);

        if (!$role) {
            return null;
        }

        # Sanitize data
        $data = $request->safe()->only('permissionIds');

        if($this->roleRepository->syncPermissions($data, $id)) {
            return $role;
        }

        return false;
    }

    /**
     * Get role
     * @param $id
     * @return Model|Collection|Builder|array|null
     */
    public function getRole($id): Model|Collection|Builder|array|null
    {
        return $this->roleRepository->find($id);
    }
}
