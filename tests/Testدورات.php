<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\دوراتController;
use App\Repository\دوراتRepository;
use App\Entity\دورة;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use PHPUnit\Framework\MockObject\MockObject;

class Testدورات extends TestCase
{
    private $controller;
    private $repository;
    private $entityManager;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(DوراتRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->controller = new دوراتController($this->repository, $this->entityManager);
    }

    public function testGetAll(): void
    {
        $expectedResponse = ['data' => []];
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn($expectedResponse['data']);

        $response = $this->controller->getAll();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($expectedResponse, json_decode($response->getContent(), true));
    }

    public function testGetById(): void
    {
        $id = 1;
        $expectedResponse = ['data' => new دورة()];
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn($expectedResponse['data']);

        $response = $this->controller->getById($id);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($expectedResponse, json_decode($response->getContent(), true));
    }

    public function testGetByIdNotFound(): void
    {
        $id = 1;
        $this->expectException(NotFoundHttpException::class);

        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(null);

        $this->controller->getById($id);
    }

    public function testCreate(): void
    {
        $data = ['name' => 'Test دورة'];
        $expectedResponse = ['data' => new دورة()];
        $this->repository->expects($this->once())
            ->method('create')
            ->with($data)
            ->willReturn($expectedResponse['data']);

        $response = $this->controller->create($data);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals($expectedResponse, json_decode($response->getContent(), true));
    }

    public function testUpdate(): void
    {
        $id = 1;
        $data = ['name' => 'Test دورة'];
        $expectedResponse = ['data' => new دورة()];
        $this->repository->expects($this->once())
            ->method('update')
            ->with($id, $data)
            ->willReturn($expectedResponse['data']);

        $response = $this->controller->update($id, $data);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($expectedResponse, json_decode($response->getContent(), true));
    }

    public function testUpdateNotFound(): void
    {
        $id = 1;
        $data = ['name' => 'Test دورة'];
        $this->expectException(NotFoundHttpException::class);

        $this->repository->expects($this->once())
            ->method('update')
            ->with($id, $data)
            ->willReturn(null);

        $this->controller->update($id, $data);
    }

    public function testDelete(): void
    {
        $id = 1;
        $this->repository->expects($this->once())
            ->method('delete')
            ->with($id);

        $response = $this->controller->delete($id);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteNotFound(): void
    {
        $id = 1;
        $this->expectException(NotFoundHttpException::class);

        $this->repository->expects($this->once())
            ->method('delete')
            ->with($id)
            ->willReturn(null);

        $this->controller->delete($id);
    }
}


This test file covers the following scenarios:

- `testGetAll`: Tests the `getAll` method of the `دوراتController` class, which should return a list of all courses.
- `testGetById`: Tests the `getById` method of the `دوراتController` class, which should return a single course by its ID.
- `testGetByIdNotFound`: Tests the `getById` method when the course with the given ID is not found.
- `testCreate`: Tests the `create` method of the `دوراتController` class, which should create a new course.
- `testUpdate`: Tests the `update` method of the `دوراتController` class, which should update an existing course.
- `testUpdateNotFound`: Tests the `update` method when the course with the given ID is not found.
- `testDelete`: Tests the `delete` method of the `دوراتController` class, which should delete a course by its ID.
- `testDeleteNotFound`: Tests the `delete` method when the course with the given ID is not found.

Note that this is a basic example and you may need to adjust the test cases based on your specific requirements.