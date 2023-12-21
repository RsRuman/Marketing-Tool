<?php

namespace Tests\Feature\User;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tests\TestCase;

class UserListTest extends TestCase
{
    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->authUser = User::create([
            'email'    => 'admin@example.com',
            'password' => bcrypt('12345678')
        ]);

        $this->role = Role::create(['name' => 'admin', 'guard_name' => 'api', 'user_id' => $this->authUser->id]);

        $this->role->syncPermissions(['view-user']);

        $this->authUser->assignRole($this->role);
    }

    /**
     * @return void
     */
    public function tearDown(): void
    {
        $this->authUser?->delete();
        $this->role?->delete();
    }

    /**
     * User list test
     * @return void
     */
    public function test_user_list_success() {
        $this->actingAs($this->authUser, 'api');

        $response = $this->getJson('api/v1/users');

        $response->assertStatus(ResponseAlias::HTTP_OK);
    }
}
