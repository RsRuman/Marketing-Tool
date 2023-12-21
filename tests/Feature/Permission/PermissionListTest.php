<?php

namespace Tests\Feature\Permission;

use App\Models\User;
use Illuminate\Http\Response;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tests\TestCase;

class PermissionListTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->authUser = User::create([
            'email'    => 'admin@example.com',
            'password' => bcrypt('12345678')
        ]);

        $this->role = Role::create(['name' => 'admin', 'guard_name' => 'api', 'user_id' => $this->authUser->id]);

        $this->role->syncPermissions(['view-permission']);

        $this->authUser->assignRole($this->role);
    }

    public function tearDown(): void
    {
        $this->authUser?->delete();
        $this->role?->delete();
    }

    public function test_permission_list_success() {
        $this->actingAs($this->authUser, 'api');

        $response = $this->getJson('api/v1/permissions');

        $response->assertStatus(ResponseAlias::HTTP_OK);
    }
}
