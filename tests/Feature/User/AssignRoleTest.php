<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tests\TestCase;

class AssignRoleTest extends TestCase
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

        $this->user = User::create([
            'email'     => 'user@example.com',
            'origin_id' => $this->authUser->id,
            'password'  => bcrypt('12345678'),
        ]);

        $this->role = Role::create(['name' => 'admin', 'guard_name' => 'api', 'user_id' => $this->authUser->id]);

        $this->role->syncPermissions(['assign-role']);

        $this->authUser->assignRole($this->role);

        $this->userRole = Role::create(['name' => 'editor', 'guard_name' => 'api', 'user_id' => $this->authUser->id]);
    }

    /**
     * Remove data
     * @return void
     */
    public function tearDown(): void
    {
        $this->authUser?->delete();
        $this->user?->delete();
        $this->role?->delete();
        $this->userRole?->delete();
    }

    /**
     * Assign roles success scenario
     * @return void
     */
    public function test_assign_roles_success() {
        $data = [
            'roleIds' => [$this->userRole->id]
        ];

        $this->actingAs($this->authUser, 'api');

        $response = $this->postJson('api/v1/users/'.$this->user->id.'/roles', $data);

        $response->assertStatus(ResponseAlias::HTTP_OK);
    }

    /**
     * Assign roles failed scenario
     * @return void
     */
    public function test_assign_roles_failed() {
        foreach ($this->getInvalidPayload() as $payload) {
            $data = $payload;

            $this->actingAs($this->authUser, 'api');

            $response = $this->postJson('api/v1/users/'.$this->user->id.'/roles', $data);

            $response->assertStatus(ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @return array
     */
    private function getInvalidPayload(): array
    {
        return [
            [],
            [
                'roleIds' => ''
            ],
            [
                'abc' => [3, 4]
            ],
        ];
    }
}
