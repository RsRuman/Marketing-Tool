<?php

namespace Feature\Role;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tests\TestCase;

class CreateRoleTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->authUser = User::create([
            'email'    => 'admin@example.com',
            'password' => bcrypt('12345678')
        ]);

        $this->role = Role::create(['name' => 'admin', 'guard_name' => 'api', 'user_id' => $this->authUser->id]);

        $this->role->syncPermissions(['create-role']);

        $this->authUser->assignRole($this->role);
    }

    public function tearDown(): void
    {
        $this->authUser?->delete();
        $this->role?->delete();
    }

    public function test_create_role_success() {
        $data = [
            'name' => 'editor'
        ];

        $this->actingAs($this->authUser, 'api');

        $response = $this->postJson('api/v1/roles', $data);

        $response->assertStatus(ResponseAlias::HTTP_CREATED);

        $role = Role::query()->find($response->json('id'));

        $role->delete();
    }

    public function test_create_role_failed() {
        foreach ($this->getInvalidPayload() as $payload) {
            # Data
            $data = $payload;

            $this->actingAs($this->authUser, 'api');

            $response = $this->postJson('api/v1/roles', $data);

            $response->assertStatus(ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }
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
