<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\طلابController;
use App\Repository\طلابRepository;
use App\Entity\طلاب;
use App\Service\طلابService;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Testطلاب extends TestCase
{
    private $controller;
    private $repository;
    private $service;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock('PDO');
        $this->repository = $this->createMock(طلابRepository::class);
        $this->service = $this->createMock(طلابService::class);
        $this->controller = new طلابController($this->repository, $this->service, $this->pdo);
    }

    public function testGetAll()
    {
        $expectedResponse = ['data' => []];
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([]);
        $response = $this->controller->getAll();
        $this->assertEquals($expectedResponse, $response->toArray());
    }

    public function testGetById()
    {
        $id = 1;
        $expectedResponse = ['data' => []];
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(new طلاب());
        $response = $this->controller->getById($id);
        $this->assertEquals($expectedResponse, $response->toArray());
    }

    public function testPost()
    {
        $data = ['name' => 'John Doe'];
        $expectedResponse = ['message' => 'Student created successfully'];
        $this->service->expects($this->once())
            ->method('create')
            ->with($data)
            ->willReturn(new طلاب());
        $response = $this->controller->post($data);
        $this->assertEquals($expectedResponse, $response->toArray());
    }

    public function testPut()
    {
        $id = 1;
        $data = ['name' => 'John Doe'];
        $expectedResponse = ['message' => 'Student updated successfully'];
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(new طلاب());
        $this->service->expects($this->once())
            ->method('update')
            ->with($id, $data)
            ->willReturn(new طلاب());
        $response = $this->controller->put($id, $data);
        $this->assertEquals($expectedResponse, $response->toArray());
    }

    public function testDelete()
    {
        $id = 1;
        $expectedResponse = ['message' => 'Student deleted successfully'];
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(new طلاب());
        $this->service->expects($this->once())
            ->method('delete')
            ->with($id)
            ->willReturn(true);
        $response = $this->controller->delete($id);
        $this->assertEquals($expectedResponse, $response->toArray());
    }

    public function testNotFoundHttpException()
    {
        $id = 1;
        $this->expectException(NotFoundHttpException::class);
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(null);
        $this->controller->getById($id);
    }
}



// App\Controller\طلابController.php

namespace App\Controller;

use App\Repository\طلابRepository;
use App\Service\طلابService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class طلابController
{
    private $repository;
    private $service;
    private $pdo;

    public function __construct(طلابRepository $repository, طلابService $service, PDO $pdo)
    {
        $this->repository = $repository;
        $this->service = $service;
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        $students = $this->repository->findAll();
        return new JsonResponse(['data' => $students]);
    }

    public function getById($id)
    {
        $student = $this->repository->find($id);
        if (!$student) {
            throw new NotFoundHttpException('Student not found');
        }
        return new JsonResponse(['data' => $student]);
    }

    public function post(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $student = $this->service->create($data);
        return new JsonResponse(['message' => 'Student created successfully']);
    }

    public function put($id, Request $request)
    {
        $student = $this->repository->find($id);
        if (!$student) {
            throw new NotFoundHttpException('Student not found');
        }
        $data = json_decode($request->getContent(), true);
        $student = $this->service->update($id, $data);
        return new JsonResponse(['message' => 'Student updated successfully']);
    }

    public function delete($id)
    {
        $student = $this->repository->find($id);
        if (!$student) {
            throw new NotFoundHttpException('Student not found');
        }
        $this->service->delete($id);
        return new JsonResponse(['message' => 'Student deleted successfully']);
    }
}