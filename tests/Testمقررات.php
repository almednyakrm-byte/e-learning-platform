<?php

namespace App\Tests\Controller;

use App\Controller\MuqarratController;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TestMuqarrat extends TestCase
{
    private $controller;
    private $pdoMock;

    protected function setUp(): void
    {
        $this->pdoMock = $this->createMock(PDO::class);
        $this->controller = new MuqarratController($this->pdoMock);
    }

    public function testGetAllMuqarrats()
    {
        $this->pdoMock->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM muqarrats')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new Request();
        $response = $this->controller->getAllMuqarrats($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetMuqarratById()
    {
        $this->pdoMock->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM muqarrats WHERE id = ?', [1])
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new Request();
        $response = $this->controller->getMuqarratById($request, 1);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testCreateMuqarrat()
    {
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO muqarrats (name, description) VALUES (?, ?)')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new Request([], [], ['name' => 'Test Muqarrat', 'description' => 'Test Description']);
        $response = $this->controller->createMuqarrat($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testUpdateMuqarrat()
    {
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('UPDATE muqarrats SET name = ?, description = ? WHERE id = ?', ['Updated Name', 'Updated Description', 1])
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new Request([], [], ['name' => 'Updated Name', 'description' => 'Updated Description']);
        $response = $this->controller->updateMuqarrat($request, 1);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testDeleteMuqarrat()
    {
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM muqarrats WHERE id = ?', [1])
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new Request();
        $response = $this->controller->deleteMuqarrat($request, 1);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(204, $response->getStatusCode());
    }
}