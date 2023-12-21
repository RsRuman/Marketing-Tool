<?php

namespace Tests\Feature\Role;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tests\TestCase;

class UpdateRoleTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->authUser = User::create([
            'email'    => 'admin@example.com',
            'password' => bcrypt('12345678')
        ]);

        $this->role = Role::create(['name' => 'admin', 'guard_name' => 'api', 'user_id' => $this->authUser->id]);

        $this->role->syncPermissions(['update-role']);

        $this->authUser->assignRole($this->role);
    }

    public function tearDown(): void
    {
        $this->authUser?->delete();
        $this->role?->delete();
    }

    /**
     * Role update success scenario
     * @return void
     */
    public function test_role_update_success() {
        $role = Role::create(['name' => 'editor', 'guard_name' => 'api', 'user_id' => $this->authUser->id]);
        $data = [
            'name' => 'editorr'
        ];

        $this->actingAs($this->authUser, 'api');

        $response = $this->putJson('api/v1/roles/'.$role->id, $data);

        $response->assertStatus(ResponseAlias::HTTP_OK);

        Role::findById($role->id)->delete();
    }

    /**
     * Role update failed scenario
     * @return void
     */

    public function test_role_update_failed() {
        $role = Role::create(['name' => 'editor', 'guard_name' => 'api', 'user_id' => $this->authUser->id]);
        foreach ($this->getInvalidPayload() as $payload) {
            $data = $payload;

            $this->actingAs($this->authUser, 'api');

            $response = $this->putJson('api/v1/roles/'.$role->id, $data);

            $response->assertStatus(ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }

        Role::findById($role->id)->delete();
    }

    private function getInvalidPayload(): array
    {

        return [
            [
                'name' => ''
            ],

            [
                'name' => 'dkfsdkfksdlkfjlsdjflsldfjlsjdfjlskdjfljsldfjlsdjfljsldfjlsjdfjslkdjfklsjdlkfjlskdflksdflksjdfkljslkdflksjdflkslkfjsjdflksdlkfj'
            ],

            [

            ]
        ];
    }
}
