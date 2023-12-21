<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Support\Facades\Event;
use Moveon\User\Http\Controllers\AuthController;
use Moveon\User\Http\Requests\UserStoreRequest;
use Moveon\User\Services\UserService;
use PHPUnit\Framework\MockObject\Exception;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class AuthControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Event::fake();
    }

    public function tearDown(): void
    {
        $role = Role::query()->where('name', 'admin')->first();
        if ($role) {
            $role->delete();
        }
    }

    /**
     * A user sign up success unit test.
     * @throws Exception
     */
    public function test_sign_up_success(): void
    {
        # Dummy payload
        $payload = [
            'first_name'            => 'John',
            'last_name'             => 'Doe',
            'user_name'             => 'JohnDoe',
            'email'                 => 'johndoe@example.com',
            'password'              => '12345678',
            'password_confirmation' => '12345678',
        ];

        # Initialize request
        $request = new UserStoreRequest($payload);

        # UserService mock
        $userServiceMock = $this->createMock(UserService::class);
        $userInstance = new User($payload);
        $userInstance->id = 1;
        $userServiceMock->expects($this->once())
            ->method('createUser')
            ->with($request)
            ->willReturn($userInstance);

        # Create auth controller
        $authController = new AuthController($userServiceMock);

        $response = $authController->signUp($request);
        $responseData = json_decode($response->getContent(), true);

        # Assertion
        $this->assertEquals(ResponseAlias::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals('John', $responseData['first_name']);
        $this->assertEquals('Doe', $responseData['last_name']);
        $this->assertEquals('JohnDoe', $responseData['user_name']);
        $this->assertEquals('johndoe@example.com', $responseData['email']);
    }

    /**
     * A user sign up failed unit test.
     * @throws Exception
     */
    public function test_sign_up_failed(): void
    {
        # Dummy payload
        $payload = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'user_name' => 'JohnDoe',
            'email' => 'johndoeexample.com',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ];

        # Initialize request
        $request = new UserStoreRequest($payload);

        # UserService mock
        $userServiceMock = $this->createMock(UserService::class);
        $userInstance = new User($payload);
        $userInstance->id = 1;
        $userServiceMock->expects($this->once())
            ->method('createUser')
            ->with($request)
            ->willReturn(false);

        # Create auth controller
        $authController = new AuthController($userServiceMock);

        $response = $authController->signUp($request);

        # Assertion
        $this->assertEquals(ResponseAlias::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
}
