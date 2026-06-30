<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\StudentsController;
use App\Repository\StudentsRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;

class TestStudents extends TestCase
{
    private $studentsController;
    private $studentsRepository;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->studentsRepository = $this->createMock(StudentsRepository::class);
        $this->studentsController = new StudentsController($this->studentsRepository);
    }

    public function testGetStudents()
    {
        $expectedResponse = ['students' => []];
        $this->studentsRepository->expects($this->once())
            ->method('getAllStudents')
            ->willReturn($expectedResponse);
        $response = $this->studentsController->getStudents();
        $this->assertEquals($expectedResponse, $response);
    }

    public function testCreateStudent()
    {
        $studentData = ['name' => 'John Doe', 'email' => 'john@example.com'];
        $expectedResponse = ['message' => 'Student created successfully'];
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO students (name, email) VALUES (:name, :email)');
        $this->pdo->expects($this->once())
            ->method('execute')
            ->with(['name' => $studentData['name'], 'email' => $studentData['email']]);
        $response = $this->studentsController->createStudent($studentData);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testUpdateStudent()
    {
        $studentId = 1;
        $studentData = ['name' => 'John Doe', 'email' => 'john@example.com'];
        $expectedResponse = ['message' => 'Student updated successfully'];
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE students SET name = :name, email = :email WHERE id = :id');
        $this->pdo->expects($this->once())
            ->method('execute')
            ->with(['name' => $studentData['name'], 'email' => $studentData['email'], 'id' => $studentId]);
        $response = $this->studentsController->updateStudent($studentId, $studentData);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testDeleteStudent()
    {
        $studentId = 1;
        $expectedResponse = ['message' => 'Student deleted successfully'];
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM students WHERE id = :id');
        $this->pdo->expects($this->once())
            ->method('execute')
            ->with(['id' => $studentId]);
        $response = $this->studentsController->deleteStudent($studentId);
        $this->assertEquals($expectedResponse, $response);
    }
}


Note: This test class assumes that the `StudentsController` class has methods for each CRUD operation and that the `StudentsRepository` class has methods for interacting with the database. The `PDO` class is used to mock the database interactions. The `createMock` method is used to create mock objects for the `PDO` and `StudentsRepository` classes. The `expects` method is used to specify the expected behavior of the mock objects. The `once` method is used to specify that the expected behavior should occur only once. The `willReturn` method is used to specify the expected response for a given method call.