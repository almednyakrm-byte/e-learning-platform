<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\دوراتController;
use App\Repository\دوراتRepository;
use App\Entity\دورة;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use PHPUnit\Framework\MockObject\MockObject;

class Testدورات extends TestCase
{
    private $controller;
    private $repository;
    private $entityManager;
    private $router;
    private $tokenStorage;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(دوراتRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->router = $this->createMock(RouterInterface::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);

        $this->controller = new دوراتController($this->repository, $this->entityManager, $this->router, $this->tokenStorage);
    }

    public function testGetAll(): void
    {
        $expectedResponse = [
            ['id' => 1, 'name' => 'Course 1'],
            ['id' => 2, 'name' => 'Course 2'],
        ];

        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn($expectedResponse);

        $response = $this->controller->getAll();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($expectedResponse), $response->getContent());
    }

    public function testGetOne(): void
    {
        $expectedResponse = ['id' => 1, 'name' => 'Course 1'];

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($expectedResponse);

        $response = $this->controller->getOne(1);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($expectedResponse), $response->getContent());
    }

    public function testGetOneNotFound(): void
    {
        $this->expectException(EntityNotFoundException::class);

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->controller->getOne(1);
    }

    public function testCreate(): void
    {
        $request = new Request();
        $request->request->set('name', 'Course 1');

        $expectedResponse = ['id' => 1, 'name' => 'Course 1'];

        $this->repository->expects($this->once())
            ->method('create')
            ->with(new دورة('Course 1'))
            ->willReturn($expectedResponse);

        $response = $this->controller->create($request);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals(json_encode($expectedResponse), $response->getContent());
    }

    public function testUpdate(): void
    {
        $request = new Request();
        $request->request->set('name', 'Course 1');

        $expectedResponse = ['id' => 1, 'name' => 'Course 1'];

        $this->repository->expects($this->once())
            ->method('update')
            ->with(1, new دورة('Course 1'))
            ->willReturn($expectedResponse);

        $response = $this->controller->update(1, $request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($expectedResponse), $response->getContent());
    }

    public function testUpdateNotFound(): void
    {
        $this->expectException(EntityNotFoundException::class);

        $request = new Request();
        $request->request->set('name', 'Course 1');

        $this->repository->expects($this->once())
            ->method('update')
            ->with(1, new دورة('Course 1'))
            ->willReturn(null);

        $this->controller->update(1, $request);
    }

    public function testDelete(): void
    {
        $expectedResponse = ['message' => 'Course deleted successfully'];

        $this->repository->expects($this->once())
            ->method('delete')
            ->with(1)
            ->willReturn($expectedResponse);

        $response = $this->controller->delete(1);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($expectedResponse), $response->getContent());
    }

    public function testDeleteNotFound(): void
    {
        $this->expectException(EntityNotFoundException::class);

        $this->repository->expects($this->once())
            ->method('delete')
            ->with(1)
            ->willReturn(null);

        $this->controller->delete(1);
    }
}


Note: This test file assumes that the `دوراتController` class has the following methods:

* `getAll()`: Returns a response with a list of courses.
* `getOne($id)`: Returns a response with a single course by ID.
* `create(Request $request)`: Creates a new course and returns a response with the created course.
* `update($id, Request $request)`: Updates an existing course and returns a response with the updated course.
* `delete($id)`: Deletes a course and returns a response with a success message.

Also, this test file assumes that the `دوراتRepository` class has the following methods:

* `findAll()`: Returns a list of all courses.
* `find($id)`: Returns a single course by ID.
* `create($course)`: Creates a new course and returns the created course.
* `update($id, $course)`: Updates an existing course and returns the updated course.
* `delete($id)`: Deletes a course and returns a success message.