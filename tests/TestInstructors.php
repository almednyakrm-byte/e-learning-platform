<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Controllers\InstructorsController;
use App\Models\InstructorsModel;
use PDO;

class TestInstructors extends TestCase
{
    private $instructorsController;
    private $instructorsModel;
    private $request;
    private $response;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->instructorsModel = new InstructorsModel($this->pdo);
        $this->instructorsController = new InstructorsController($this->instructorsModel);
        $this->request = $this->createMock(ServerRequestInterface::class);
        $this->response = $this->createMock(ResponseInterface::class);
    }

    public function testGetInstructors()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        $stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'John Doe'],
                ['id' => 2, 'name' => 'Jane Doe'],
            ]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt);

        $response = $this->instructorsController->getInstructors($this->request, $this->response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetInstructorById()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        $stmt->expects($this->once())
            ->method('fetch')
            ->willReturn(['id' => 1, 'name' => 'John Doe']);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt);

        $this->request->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(1);

        $response = $this->instructorsController->getInstructorById($this->request, $this->response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testCreateInstructor()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt);

        $this->request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['name' => 'John Doe']);

        $response = $this->instructorsController->createInstructor($this->request, $this->response);
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testUpdateInstructor()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt);

        $this->request->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(1);

        $this->request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['name' => 'Jane Doe']);

        $response = $this->instructorsController->updateInstructor($this->request, $this->response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testDeleteInstructor()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt);

        $this->request->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(1);

        $response = $this->instructorsController->deleteInstructor($this->request, $this->response);
        $this->assertEquals(204, $response->getStatusCode());
    }
}