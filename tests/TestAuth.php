<?php

namespace App\Tests\Unit\Auth;

use PHPUnit\Framework\TestCase;
use App\Auth\AuthService;
use App\Auth\AuthRepository;
use App\Auth\User;
use Mockery;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;

class TestAuth extends TestCase
{
    /**
     * @var LegacyMockInterface|MockInterface|AuthRepository
     */
    protected $authRepository;

    /**
     * @var LegacyMockInterface|MockInterface|AuthService
     */
    protected $authService;

    protected function setUp(): void
    {
        $this->authRepository = Mockery::mock(AuthRepository::class);
        $this->authService = Mockery::mock(AuthService::class);
    }

    public function testLoginSuccess()
    {
        // Arrange
        $username = 'johnDoe';
        $password = 'password123';
        $expectedUser = new User($username, $password);

        $this->authRepository->shouldReceive('getUserByUsername')->with($username)->andReturn($expectedUser);
        $this->authRepository->shouldReceive('verifyPassword')->with($expectedUser, $password)->andReturn(true);

        // Act
        $this->authService->shouldReceive('login')->with($expectedUser)->once();

        // Assert
        $this->authService->login($username, $password);
        $this->assertTrue($this->authService->isLoggedIn());
    }

    public function testLoginFailure()
    {
        // Arrange
        $username = 'johnDoe';
        $password = 'password123';
        $expectedUser = new User($username, $password);

        $this->authRepository->shouldReceive('getUserByUsername')->with($username)->andReturn($expectedUser);
        $this->authRepository->shouldReceive('verifyPassword')->with($expectedUser, $password)->andReturn(false);

        // Act
        $this->authService->login($username, $password);

        // Assert
        $this->assertFalse($this->authService->isLoggedIn());
    }

    public function testRegisterSuccess()
    {
        // Arrange
        $username = 'johnDoe';
        $password = 'password123';
        $expectedUser = new User($username, $password);

        $this->authRepository->shouldReceive('getUserByUsername')->with($username)->andReturn(null);
        $this->authRepository->shouldReceive('createUser')->with($expectedUser)->andReturn($expectedUser);

        // Act
        $this->authService->shouldReceive('register')->with($expectedUser)->once();

        // Assert
        $this->authService->register($username, $password);
        $this->assertTrue($this->authService->isLoggedIn());
    }

    public function testRegisterFailure()
    {
        // Arrange
        $username = 'johnDoe';
        $password = 'password123';
        $expectedUser = new User($username, $password);

        $this->authRepository->shouldReceive('getUserByUsername')->with($username)->andReturn($expectedUser);

        // Act
        $this->authService->register($username, $password);

        // Assert
        $this->assertFalse($this->authService->isLoggedIn());
    }
}


This test file covers the following scenarios:

- `testLoginSuccess`: Tests that the login method succeeds when the user exists and the password is correct.
- `testLoginFailure`: Tests that the login method fails when the user exists but the password is incorrect.
- `testRegisterSuccess`: Tests that the register method succeeds when the user does not exist and the password is correct.
- `testRegisterFailure`: Tests that the register method fails when the user already exists.

Note: The `AuthRepository` and `AuthService` classes are assumed to be implemented elsewhere in the application. This test file only focuses on the authentication logic.