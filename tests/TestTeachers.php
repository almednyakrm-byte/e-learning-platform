<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\TeachersController;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use PDOStatement;

class TestTeachers extends TestCase
{
    private $teachersController;
    private $pdoMock;

    protected function setUp(): void
    {
        $this->pdoMock = $this->createMock(PDO::class);
        $this->teachersController = new TeachersController($this->pdoMock);
    }

    public function testGetTeachers()
    {
        $expectedResponse = ['teachers' => ['teacher1', 'teacher2']];
        $this->pdoMock->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM teachers')
            ->willReturn($this->createMock(PDOStatement::class));
        $this->pdoMock->expects($this->once())
            ->method('fetch')
            ->willReturn(['teacher1', 'teacher2']);
        $response = $this->teachersController->getTeachers();
        $this->assertEquals($expectedResponse, $response);
    }

    public function testCreateTeacher()
    {
        $expectedResponse = ['message' => 'Teacher created successfully'];
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO teachers (name) VALUES (:name)')
            ->willReturn($this->createMock(PDOStatement::class));
        $this->pdoMock->expects($this->once())
            ->method('execute')
            ->with(['name' => 'teacher1']);
        $response = $this->teachersController->createTeacher('teacher1');
        $this->assertEquals($expectedResponse, $response);
    }

    public function testUpdateTeacher()
    {
        $expectedResponse = ['message' => 'Teacher updated successfully'];
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('UPDATE teachers SET name = :name WHERE id = :id')
            ->willReturn($this->createMock(PDOStatement::class));
        $this->pdoMock->expects($this->once())
            ->method('execute')
            ->with(['name' => 'teacher1', 'id' => 1]);
        $response = $this->teachersController->updateTeacher(1, 'teacher1');
        $this->assertEquals($expectedResponse, $response);
    }

    public function testDeleteTeacher()
    {
        $expectedResponse = ['message' => 'Teacher deleted successfully'];
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM teachers WHERE id = :id')
            ->willReturn($this->createMock(PDOStatement::class));
        $this->pdoMock->expects($this->once())
            ->method('execute')
            ->with(['id' => 1]);
        $response = $this->teachersController->deleteTeacher(1);
        $this->assertEquals($expectedResponse, $response);
    }
}


This test file includes tests for the following CRUD operations:

- `getTeachers`: Tests the GET request to retrieve all teachers.
- `createTeacher`: Tests the POST request to create a new teacher.
- `updateTeacher`: Tests the PUT request to update an existing teacher.
- `deleteTeacher`: Tests the DELETE request to delete a teacher.

Each test method uses the `createMock` method to create a mock PDO object, which is then used to simulate the database interactions. The expected responses are compared with the actual responses returned by the controller methods.