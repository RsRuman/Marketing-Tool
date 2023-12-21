<?php

namespace Feature\Role;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tests\TestCase;

class RoleListTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->authUser = User::create([
            'email' => 'admin@example.com',
            'password' => bcrypt('12345678')
        ]);

        $this->role = Role::create(['name' => 'admin', 'guard_name' => 'api', 'user_id' => $this->authUser->id]);

        $this->role->syncPermissions(['view-role']);

        $this->authUser->assignRole($this->role);
    }

    public function tearDown(): void
    {
        $this->authUser?->delete();
        $this->role?->delete();
    }

    public function test_role_list_success() {
        $this->actingAs($this->authUser, 'api');

        $response = $this->getJson('api/v1/roles');

        $response->assertStatus(ResponseAlias::HTTP_OK);
    }
}
