<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\دوراتController;
use App\Repository\دوراتRepository;
use App\Entity\دورة;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Testدورات extends TestCase
{
    private $controller;
    private $repository;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock('PDO');
        $this->repository = $this->createMock(دوراتRepository::class);
        $this->controller = new دوراتController($this->repository);
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

    public function testGetOne(): void
    {
        $id = 1;
        $expectedResponse = ['data' => []];
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn($expectedResponse['data']);

        $response = $this->controller->getOne($id);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($expectedResponse, json_decode($response->getContent(), true));
    }

    public function testCreate(): void
    {
        $data = ['name' => 'Test دورة'];
        $expectedResponse = ['data' => []];
        $this->repository->expects($this->once())
            ->method('create')
            ->with($data)
            ->willReturn($expectedResponse['data']);

        $request = new Request([], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));
        $response = $this->controller->create($request);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals($expectedResponse, json_decode($response->getContent(), true));
    }

    public function testUpdate(): void
    {
        $id = 1;
        $data = ['name' => 'Test دورة'];
        $expectedResponse = ['data' => []];
        $this->repository->expects($this->once())
            ->method('update')
            ->with($id, $data)
            ->willReturn($expectedResponse['data']);

        $request = new Request([], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));
        $response = $this->controller->update($id, $request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($expectedResponse, json_decode($response->getContent(), true));
    }

    public function testDelete(): void
    {
        $id = 1;
        $expectedResponse = ['data' => []];
        $this->repository->expects($this->once())
            ->method('delete')
            ->with($id)
            ->willReturn($expectedResponse['data']);

        $response = $this->controller->delete($id);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($expectedResponse, json_decode($response->getContent(), true));
    }

    public function testGetOneNotFound(): void
    {
        $id = 1;
        $this->expectException(NotFoundHttpException::class);
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(null);

        $this->controller->getOne($id);
    }
}