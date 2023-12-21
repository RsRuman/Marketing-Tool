<?php

namespace Tests\Feature\User;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tests\TestCase;

class CreateUserTest extends TestCase
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

        $this->role = Role::create(['name' => 'admin', 'guard_name' => 'api', 'user_id' => $this->authUser->id]);

        $this->role->syncPermissions(['create-user']);

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
    }

    /**
     * User create success scenario
     * @return void
     */
    public function test_create_user_success() {
        # Payload
        $data = [
            "email" => "user4@example.com",
            "password" => "12345678",
            "password_confirmation" => "12345678"
        ];

        $this->actingAs($this->authUser, 'api');

        $response = $this->postJson('api/v1/users', $data);

        $response->assertStatus(ResponseAlias::HTTP_CREATED);

        $userToDelete = User::find($response->json('id'));
        if ($userToDelete) {
            $userToDelete->delete();
        }
    }

    /**
     * User create failed scenario
     * @return void
     */
    public function test_create_user_failed() {
        foreach ($this->getInvalidPayload() as $payload) {
            # Payload
            $data = $payload;

            $this->actingAs($this->authUser, 'api');

            $response = $this->postJson('api/v1/users', (array) $data);

            $response->assertStatus(ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }
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
