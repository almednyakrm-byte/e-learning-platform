<?php

namespace App\Tests\Controller;

use App\Controller\ProfessorController;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\MockBuilder;
use PDO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TestProfessorController extends TestCase
{
    private $controller;
    private $pdoMock;

    protected function setUp(): void
    {
        $this->controller = new ProfessorController();
        $this->pdoMock = $this->createMock(PDO::class);
    }

    public function testGetProfessors()
    {
        $this->pdoMock->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM professors')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new Request();
        $response = $this->controller->getProfessors($request, $this->pdoMock);
        $this->assertInstanceOf(Response::class, $response);
    }

    public function testCreateProfessor()
    {
        $professorData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ];

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO professors (name, email) VALUES (:name, :email)')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new Request([], [], ['professor' => $professorData]);
        $response = $this->controller->createProfessor($request, $this->pdoMock);
        $this->assertInstanceOf(Response::class, $response);
    }

    public function testUpdateProfessor()
    {
        $professorId = 1;
        $professorData = [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
        ];

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('UPDATE professors SET name = :name, email = :email WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new Request([], [], ['professor' => $professorData]);
        $response = $this->controller->updateProfessor($professorId, $request, $this->pdoMock);
        $this->assertInstanceOf(Response::class, $response);
    }

    public function testDeleteProfessor()
    {
        $professorId = 1;

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM professors WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new Request();
        $response = $this->controller->deleteProfessor($professorId, $request, $this->pdoMock);
        $this->assertInstanceOf(Response::class, $response);
    }
}


Note: This test class assumes that the `ProfessorController` class has methods `getProfessors`, `createProfessor`, `updateProfessor`, and `deleteProfessor` which handle the respective CRUD operations. The test class also assumes that the `ProfessorController` class takes a PDO instance as a dependency.