<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use PDO;

class TestStudents extends TestCase
{
    private $pdo;
    private $request;
    private $response;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->request = $this->createMock(ServerRequestInterface::class);
        $this->response = $this->createMock(ResponseInterface::class);
    }

    public function testGetStudents()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with($this->equalTo([]));

        $stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'John Doe'],
                ['id' => 2, 'name' => 'Jane Doe'],
            ]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo('SELECT * FROM students'))
            ->willReturn($stmt);

        $students = new Students($this->pdo);
        $result = $students->getStudents($this->request, $this->response);

        $this->assertIsArray($result);
        $this->assertCount(2, $result);
    }

    public function testGetStudentById()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with($this->equalTo([1]));

        $stmt->expects($this->once())
            ->method('fetch')
            ->willReturn(['id' => 1, 'name' => 'John Doe']);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo('SELECT * FROM students WHERE id = ?'))
            ->willReturn($stmt);

        $students = new Students($this->pdo);
        $result = $students->getStudentById($this->request, $this->response, 1);

        $this->assertIsArray($result);
        $this->assertEquals(1, $result['id']);
    }

    public function testCreateStudent()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with($this->equalTo(['John Doe']));

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo('INSERT INTO students (name) VALUES (?)'))
            ->willReturn($stmt);

        $students = new Students($this->pdo);
        $result = $students->createStudent($this->request, $this->response, ['name' => 'John Doe']);

        $this->assertTrue($result);
    }

    public function testUpdateStudent()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with($this->equalTo(['John Doe', 1]));

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo('UPDATE students SET name = ? WHERE id = ?'))
            ->willReturn($stmt);

        $students = new Students($this->pdo);
        $result = $students->updateStudent($this->request, $this->response, 1, ['name' => 'John Doe']);

        $this->assertTrue($result);
    }

    public function testDeleteStudent()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with($this->equalTo([1]));

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo('DELETE FROM students WHERE id = ?'))
            ->willReturn($stmt);

        $students = new Students($this->pdo);
        $result = $students->deleteStudent($this->request, $this->response, 1);

        $this->assertTrue($result);
    }
}