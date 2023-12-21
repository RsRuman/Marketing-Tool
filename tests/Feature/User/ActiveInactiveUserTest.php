<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tests\TestCase;

class ActiveInactiveUserTest extends TestCase
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

        $this->role->syncPermissions(['inactive-user']);

        $this->authUser->assignRole($this->role);
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
    }

    /**
     * User active inactive success scenario
     * @return void
     */
    public function test_active_inactive_user_success() {
        $status = [
            'active',
            'inactive'
        ];

        $data = [
            'status' => $status[array_rand($status)]
        ];

        $this->actingAs($this->authUser, 'api');

        $response = $this->postJson('api/v1/users/'. $this->user->id .'/status', $data);

        $response->assertStatus(ResponseAlias::HTTP_OK);
    }

    /**
     * User active inactive failed scenario
     * @return void
     */
    public function test_active_inactive_user_failed() {

        foreach ($this->getInvalidPayload() as $payload) {
            $data = $payload;

            $this->actingAs($this->authUser, 'api');

            $response = $this->postJson('api/v1/users/'. $this->user->id . '/status', $data);

            $response->assertStatus(ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    private function getInvalidPayload(): array
    {
        return [
          [],
          [
              'status' => 'failed_status'
          ]
        ];
    }
}
