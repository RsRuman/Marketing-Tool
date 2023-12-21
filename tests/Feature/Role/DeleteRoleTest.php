<?php

namespace Tests\Feature\Role;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tests\TestCase;

class DeleteRoleTest extends TestCase
{
    /**
     * Setup data
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->authUser = User::create([
            'email'     => 'admin@example.com',
            'password'  => bcrypt('12345678'),
        ]);

        $this->role = Role::create(['name' => 'admin', 'guard_name' => 'api', 'user_id' => $this->authUser->id]);

        $this->roleTest = Role::create(['name' => 'editor', 'guard_name' => 'api', 'user_id' => $this->authUser->id]);

        $this->role->syncPermissions(['delete-role']);

        $this->authUser->assignRole($this->role);
    }

    /**
     * Remove data
     * @return void
     */
    public function tearDown(): void
    {
        $this->authUser?->delete();
        $this->role?->delete();
        $this->roleTest?->delete();
    }

    /**
     * Role delete test success scenario
     * @return void
     */
    public function test_delete_role_success() {
        $this->actingAs($this->authUser, 'api');

        $response = $this->deleteJson('api/v1/roles/'.$this->roleTest->id);

        $response->assertStatus(ResponseAlias::HTTP_OK);
    }

    /**
     * Role delete test failed scenario
     * @return void
     */
    public function test_delete_role_failed() {
        $this->actingAs($this->authUser, 'api');

        $response = $this->deleteJson('api/v1/roles/5');

        $response->assertStatus(ResponseAlias::HTTP_NOT_FOUND);
    }
}
