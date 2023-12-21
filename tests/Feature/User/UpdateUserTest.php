<?php

namespace Feature\User;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tests\TestCase;

class UpdateUserTest extends TestCase
{
    /**
     * Setup data
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->authUser = User::create([
            'email'    => 'admin@example.com',
            'password' => bcrypt('12345678'),
        ]);

        $this->user = User::create([
            'origin_id' => $this->authUser->id,
            'email'     => 'test@example.com',
            'password'  => bcrypt('12345678'),
        ]);

        $this->role = Role::create(['name' => 'admin', 'guard_name' => 'api', 'user_id' => $this->authUser->id]);

        $this->role->syncPermissions(['update-user']);

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
     * User update success scenario
     * @return void
     */
    public function test_user_update_success() {
        # Payload
        $data = [
            "first_name" => "hello",
            "last_name" => "test",
            "user_name" => "HelloTest",
            "email" => "test@example.com",
            "password" => "123456789",
            "password_confirmation" => "123456789"
        ];
        $this->actingAs($this->authUser, 'api');

        $response = $this->putJson('api/v1/users/'.$this->user->id, $data);

        $response->assertStatus(ResponseAlias::HTTP_OK);
    }

    public function test_user_update_failed() {
        # Payload
        $data = $this->getInvalidPayload();

        $this->actingAs($this->authUser, 'api');

        $response = $this->putJson('api/v1/users/'.$this->user->id, $data);

        $response->assertStatus(ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @return string[]
     */
    private function getInvalidPayload(): array
    {

        return [
            [
                "email"            => "user2example.com",
                "password"         => "123456789",
                "password_confirmation" => "123456789"
            ],

            [
                "email"            => "admin@example.com",
                "password"         => "123456789",
                "password_confirmation" => "123456789"
            ],

            [
                "email"            => "",
                "password"         => "123456789",
                "password_confirmation" => "123456789"
            ],

            [
                "email"            => "user2@example.com",
                "password"         => "12345678",
                "password_confirmation" => "123456789"
            ],

            [
                "email"            => "user2@example.com",
                "password"         => "123456789",
                "password_confirmation" => "12345678"
            ],

            [
                "email"            => "user2@example.com",
                "password"         => "123456789",
                "password_confirmation" => ""
            ],
            [
                "email"            => "user2@example.com",
                "password"         => "",
                "password_confirmation" => "123456789"
            ],

            [
                "email"            => "",
                "password"         => "",
                "password_confirmation" => ""
            ],

            [

            ]
        ];
    }
}
