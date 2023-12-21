<?php

namespace Tests\Feature\User;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tests\TestCase;

class ShowUserTest extends TestCase
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

        $this->role->syncPermissions(['view-user']);

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
     * Show user test success scenario
     * @return void
     */
    public function test_show_user_success() {
        $this->actingAs($this->authUser, 'api');

        $response = $this->getJson('api/v1/users/'.$this->user->id);

        $response->assertStatus(ResponseAlias::HTTP_OK);
    }

    /**
     * Show user test failed scenario
     * @return void
     */
    public function test_show_user_failed() {
        $this->actingAs($this->authUser, 'api');

        $response = $this->getJson('api/v1/users/3');

        $response->assertStatus(ResponseAlias::HTTP_NOT_FOUND);
    }
}
