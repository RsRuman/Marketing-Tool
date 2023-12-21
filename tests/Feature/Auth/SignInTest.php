<?php

namespace Feature\Auth;

use App\Models\User;
use Tests\TestCase;

class SignInTest extends TestCase
{
    /**
     * Setup data
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'email'    => 'test@example.com',
            'password' => bcrypt('12345678'),
        ]);
    }

    /**
     * Remove data
     * @return void
     */
    public function tearDown(): void
    {
        if ($this->user) {
            $this->user->delete();
        }
    }

    /**
     * A user sign in test success scenario.
     */
    public function test_user_sign_success(): void
    {
        # Payload
        $data = [
            'email' => 'test@example.com',
            'password' => '12345678'
        ];

        $response = $this->postJson('api/v1/sign-in', $data);

        $response->assertStatus(200);
    }

    /**
     * A user sign in test failed scenario.
     */
    public function test_user_sign_in_failed(): void
    {
        foreach ($this->getInvalidPayload() as $payload) {
            # Payload
            $data = $payload;

            $response = $this->postJson('api/v1/sign-in', (array) $data);

            $response->assertStatus(422);
        }
    }

    /**
     * @return string[]
     */
    private function getInvalidPayload(): array
    {

        return [
            [
                'email' => 'test@example.com',
                'password' => '',
            ],

            [
                'email' => '',
                'password' => '12345678',
            ],

            [
                'email' => 'testexample.com',
                'password' => '12345678',
            ],

            [
                'email' => 'test@example.com',
                'password' => '123456',
            ],
            [

            ]
        ];
    }

}

