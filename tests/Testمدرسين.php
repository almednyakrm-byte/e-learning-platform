<?php

namespace App\Tests\Controller;

use App\Controller\MadrasatController;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TestMadrasatController extends TestCase
{
    private $controller;
    private $pdoMock;

    protected function setUp(): void
    {
        $this->pdoMock = $this->createMock(PDO::class);
        $this->controller = new MadrasatController($this->pdoMock);
    }

    public function testGetAllMadrasat(): void
    {
        $this->pdoMock->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM madrasat')
            ->willReturn($this->createMock(\PDOStatement::class));

        $response = $this->controller->getAllMadrasat();
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testCreateMadrasat(): void
    {
        $request = new Request([], [], [], [], [], ['CONTENT_TYPE' => 'application/json'], '{"name": "Madrasat 1"}');
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO madrasat (name) VALUES (:name)')
            ->willReturn($this->createMock(\PDOStatement::class));
        $this->pdoMock->expects($this->once())
            ->method('commit');

        $response = $this->controller->createMadrasat($request);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUpdateMadrasat(): void
    {
        $request = new Request([], [], [], [], [], ['CONTENT_TYPE' => 'application/json'], '{"name": "Madrasat 1"}');
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('UPDATE madrasat SET name = :name WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));
        $this->pdoMock->expects($this->once())
            ->method('commit');

        $response = $this->controller->updateMadrasat(1, $request);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDeleteMadrasat(): void
    {
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM madrasat WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));
        $this->pdoMock->expects($this->once())
            ->method('commit');

        $response = $this->controller->deleteMadrasat(1);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
}


This test file covers the CRUD API operations on the 'مدرسين' module using mocked PDO statements. It includes tests for GET, POST, PUT, and DELETE requests.