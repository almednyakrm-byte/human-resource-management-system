<?php

namespace App\Tests\Auth;

use App\Auth\AuthService;
use App\Auth\AuthRepository;
use App\Auth\User;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\MockBuilder;
use PHPUnit\Framework\MockObject\MockObject;

class TestAuth extends TestCase
{
    private $authService;
    private $authRepository;

    protected function setUp(): void
    {
        $this->authRepository = $this->createMock(AuthRepository::class);
        $this->authService = new AuthService($this->authRepository);
    }

    public function testLoginSuccess()
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->authRepository->expects($this->once())
            ->method('getUserByUsername')
            ->with($username)
            ->willReturn(new User($username, $password));

        $this->authRepository->expects($this->once())
            ->method('verifyPassword')
            ->with($username, $password)
            ->willReturn(true);

        $this->authService->login($username, $password);

        $this->assertTrue($this->authService->isLoggedIn());
    }

    public function testLoginFailure()
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->authRepository->expects($this->once())
            ->method('getUserByUsername')
            ->with($username)
            ->willReturn(null);

        $this->authService->login($username, $password);

        $this->assertFalse($this->authService->isLoggedIn());
    }

    public function testRegisterSuccess()
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->authRepository->expects($this->once())
            ->method('getUserByUsername')
            ->with($username)
            ->willReturn(null);

        $this->authRepository->expects($this->once())
            ->method('createUser')
            ->with($username, $password)
            ->willReturn(new User($username, $password));

        $this->authService->register($username, $password);

        $this->assertTrue($this->authService->isLoggedIn());
    }

    public function testRegisterFailure()
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->authRepository->expects($this->once())
            ->method('getUserByUsername')
            ->with($username)
            ->willReturn(new User($username, $password));

        $this->authService->register($username, $password);

        $this->assertFalse($this->authService->isLoggedIn());
    }
}


This test file covers the following scenarios:

1. Successful login with correct credentials.
2. Failed login with incorrect credentials.
3. Successful registration with new credentials.
4. Failed registration with existing credentials.

Each test method uses the `createMock` method to create a mock object for the `AuthRepository` class, which is then used to set up expectations for the methods that are called during the authentication process. The `assertEquals` and `assertTrue` assertions are used to verify the expected behavior.