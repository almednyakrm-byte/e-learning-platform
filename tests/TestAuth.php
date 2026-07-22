<?php

namespace App\Tests\Unit\Auth;

use App\Auth\AuthService;
use App\Auth\AuthRepository;
use App\Auth\User;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\MockBuilder;

class TestAuth extends TestCase
{
    private $authService;
    private $authRepository;
    private $mockUser;

    protected function setUp(): void
    {
        $this->authRepository = $this->createMock(AuthRepository::class);
        $this->authService = new AuthService($this->authRepository);
        $this->mockUser = $this->createMock(User::class);
    }

    public function testLoginSuccess()
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->mockUser->expects($this->once())
            ->method('login')
            ->with($username, $password)
            ->willReturn(true);

        $this->authRepository->expects($this->once())
            ->method('getUserByUsername')
            ->with($username)
            ->willReturn($this->mockUser);

        $result = $this->authService->login($username, $password);
        $this->assertTrue($result);
    }

    public function testLoginFailure()
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->mockUser->expects($this->once())
            ->method('login')
            ->with($username, $password)
            ->willReturn(false);

        $this->authRepository->expects($this->once())
            ->method('getUserByUsername')
            ->with($username)
            ->willReturn($this->mockUser);

        $result = $this->authService->login($username, $password);
        $this->assertFalse($result);
    }

    public function testRegisterSuccess()
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->mockUser->expects($this->once())
            ->method('register')
            ->with($username, $password)
            ->willReturn(true);

        $this->authRepository->expects($this->once())
            ->method('getUserByUsername')
            ->with($username)
            ->willReturn(null);

        $result = $this->authService->register($username, $password);
        $this->assertTrue($result);
    }

    public function testRegisterFailure()
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->mockUser->expects($this->once())
            ->method('register')
            ->with($username, $password)
            ->willReturn(false);

        $this->authRepository->expects($this->once())
            ->method('getUserByUsername')
            ->with($username)
            ->willReturn(null);

        $result = $this->authService->register($username, $password);
        $this->assertFalse($result);
    }
}


This test file covers the following scenarios:

- `testLoginSuccess`: Tests that the `login` method of the `AuthService` class returns `true` when the user credentials are correct.
- `testLoginFailure`: Tests that the `login` method of the `AuthService` class returns `false` when the user credentials are incorrect.
- `testRegisterSuccess`: Tests that the `register` method of the `AuthService` class returns `true` when the user registration is successful.
- `testRegisterFailure`: Tests that the `register` method of the `AuthService` class returns `false` when the user registration fails.

Note that this test file assumes that the `AuthService` class and the `AuthRepository` class are properly implemented and that the `User` class has the necessary methods (`login` and `register`).