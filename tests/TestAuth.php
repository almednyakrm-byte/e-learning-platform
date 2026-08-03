<?php

namespace App\Tests\Unit\Auth;

use App\Auth\AuthService;
use App\Auth\AuthRepository;
use App\Auth\User;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\MockBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\UnitOfWork;
use Doctrine\ORM\PersistentCollection;
use Doctrine\ORM\Proxy\Proxy;

class TestAuth extends TestCase
{
    private $authService;
    private $authRepository;
    private $entityManager;

    public function setUp(): void
    {
        $this->authRepository = $this->createMock(AuthRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->authService = new AuthService($this->authRepository, $this->entityManager);
    }

    public function testLoginSuccess()
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->authRepository->expects($this->once())
            ->method('findUserByUsername')
            ->with($username)
            ->willReturn($this->createMock(User::class));

        $this->authRepository->expects($this->once())
            ->method('validateUserPassword')
            ->with($this->createMock(User::class), $password)
            ->willReturn(true);

        $this->authService->login($username, $password);

        $this->assertTrue($this->authService->isLoggedIn());
    }

    public function testLoginFailure()
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->authRepository->expects($this->once())
            ->method('findUserByUsername')
            ->with($username)
            ->willReturn($this->createMock(User::class));

        $this->authRepository->expects($this->once())
            ->method('validateUserPassword')
            ->with($this->createMock(User::class), $password)
            ->willReturn(false);

        $this->authService->login($username, $password);

        $this->assertFalse($this->authService->isLoggedIn());
    }

    public function testRegisterSuccess()
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->authRepository->expects($this->once())
            ->method('findUserByUsername')
            ->with($username)
            ->willReturn(null);

        $this->authRepository->expects($this->once())
            ->method('createUser')
            ->with($username, $password)
            ->willReturn($this->createMock(User::class));

        $this->authService->register($username, $password);

        $this->assertTrue($this->authService->isLoggedIn());
    }

    public function testRegisterFailure()
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->authRepository->expects($this->once())
            ->method('findUserByUsername')
            ->with($username)
            ->willReturn($this->createMock(User::class));

        $this->authService->register($username, $password);

        $this->assertFalse($this->authService->isLoggedIn());
    }
}


This test file covers the following scenarios:

1.  Successful login with valid credentials.
2.  Failed login with invalid credentials.
3.  Successful registration with valid credentials.
4.  Failed registration with existing username.

Each test method uses PHPUnit's mocking capabilities to isolate the dependencies of the `AuthService` class and focus on the behavior of the class under test. The assertions used in the test methods verify the expected behavior of the `AuthService` class.