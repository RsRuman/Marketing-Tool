<?php

namespace Feature\Auth;

use App\Models\User;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tests\TestCase;

class SignOutTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->authUser = User::create([
            'email' => 'admin@example.com',
            'password' => bcrypt('12345678')
        ]);
    }

    public function tearDown(): void
    {
        $this->authUser?->delete();
    }

    /**
     * User sign out success scenario
     * @return void
     */
    public function test_user_sign_out_success() {
        $this->actingAs($this->authUser, 'api');

        $response = $this->postJson('api/v1/sign-out');

        $response->assertStatus(ResponseAlias::HTTP_OK);
    }

    /**
     * User sign out failed scenario
     * @return void
     */
    public function test_user_sign_out_failed() {
        $response = $this->postJson('api/v1/sign-out');
        $response->assertStatus(ResponseAlias::HTTP_UNAUTHORIZED);
    }
}
