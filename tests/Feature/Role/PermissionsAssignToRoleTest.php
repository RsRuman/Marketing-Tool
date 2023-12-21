<?php

namespace Feature\Role;

use App\Models\User;
use Illuminate\Http\Response;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tests\TestCase;

class PermissionsAssignToRoleTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->authUser = User::create([
            'email'    => 'admin@example.com',
            'password' => bcrypt('12345678')
        ]);

        $this->role = Role::create(['name' => 'admin', 'guard_name' => 'api', 'user_id' => $this->authUser->id]);

        $this->role->syncPermissions(['assign-permission']);

        $this->authUser->assignRole($this->role);

        $this->permission = Permission::create(['name' => 'test-permission', 'guard_name' => 'api', 'user_id' => $this->authUser->id]);
    }

    public function tearDown(): void
    {
        $this->authUser?->delete();
        $this->role?->delete();
        $this->permission?->delete();
    }

    /**
     * Permission assign to role test success scenario
     * @return void
     */
    public function test_permissions_assign_to_role_success() {
        $data = [
            'permissionIds' => [$this->permission->id]
        ];

        $this->actingAs($this->authUser, 'api');

        $response = $this->postJson('api/v1/roles/'.$this->role->id.'/permissions', $data);

        $response->assertStatus(ResponseAlias::HTTP_OK);
    }

    /**
     * Permission assign to role test failed scenario
     * @return void
     */
    public function test_permissions_assign_to_role_failed() {
        $data = [
            'permissionIds' => [1000, 1001, 1003]
        ];

        $this->actingAs($this->authUser, 'api');

        $response = $this->postJson('api/v1/roles/'.$this->role->id.'/permissions', $data);

        $response->assertStatus(ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
    }
}
