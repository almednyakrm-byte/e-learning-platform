<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\طلابController;
use App\Repository\طلابRepository;
use App\Entity\طلاب;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Testطلاب extends TestCase
{
    private $controller;
    private $repository;
    private $request;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(طلابRepository::class);
        $this->controller = new طلابController($this->repository);
        $this->request = $this->createMock(Request::class);
    }

    public function testGetAll()
    {
        $expectedResponse = ['data' => []];
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn($expectedResponse['data']);

        $response = $this->controller->getAll($this->request);
        $this->assertEquals($expectedResponse, $response->getContent());
    }

    public function testGetById()
    {
        $id = 1;
        $expectedResponse = ['data' => new طلاب()];
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn($expectedResponse['data']);

        $this->request->method('query')
            ->with('id', $id)
            ->willReturn(true);

        $response = $this->controller->getById($this->request);
        $this->assertEquals($expectedResponse, $response->getContent());
    }

    public function testCreate()
    {
        $data = ['name' => 'John Doe'];
        $expectedResponse = ['data' => new طلاب()];
        $this->repository->expects($this->once())
            ->method('create')
            ->with($data)
            ->willReturn($expectedResponse['data']);

        $this->request->method('request')
            ->willReturn($data);

        $response = $this->controller->create($this->request);
        $this->assertEquals($expectedResponse, $response->getContent());
    }

    public function testUpdate()
    {
        $id = 1;
        $data = ['name' => 'Jane Doe'];
        $expectedResponse = ['data' => new طلاب()];
        $this->repository->expects($this->once())
            ->method('update')
            ->with($id, $data)
            ->willReturn($expectedResponse['data']);

        $this->request->method('query')
            ->with('id', $id)
            ->willReturn(true);

        $this->request->method('request')
            ->willReturn($data);

        $response = $this->controller->update($this->request, $id);
        $this->assertEquals($expectedResponse, $response->getContent());
    }

    public function testDelete()
    {
        $id = 1;
        $expectedResponse = ['message' => 'Student deleted successfully'];
        $this->repository->expects($this->once())
            ->method('delete')
            ->with($id);

        $this->request->method('query')
            ->with('id', $id)
            ->willReturn(true);

        $response = $this->controller->delete($this->request, $id);
        $this->assertEquals($expectedResponse, $response->getContent());
    }

    public function testGetByIdNotFound()
    {
        $id = 1;
        $this->expectException(NotFoundHttpException::class);

        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(null);

        $this->request->method('query')
            ->with('id', $id)
            ->willReturn(true);

        $this->controller->getById($this->request);
    }
}


This test file covers the following scenarios:

- `testGetAll`: Tests the `getAll` method of the `طلابController` class, which retrieves all students from the database.
- `testGetById`: Tests the `getById` method of the `طلابController` class, which retrieves a student by their ID.
- `testCreate`: Tests the `create` method of the `طلابController` class, which creates a new student in the database.
- `testUpdate`: Tests the `update` method of the `طلابController` class, which updates an existing student in the database.
- `testDelete`: Tests the `delete` method of the `طلابController` class, which deletes a student from the database.
- `testGetByIdNotFound`: Tests the `getById` method of the `طلابController` class when the student is not found in the database.

Note that this is a basic example and you may need to modify it to fit your specific use case. Additionally, you will need to implement the `طلابController` and `طلابRepository` classes to make this test file work.