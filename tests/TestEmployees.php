<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use PDOStatement;

class TestEmployees extends TestCase
{
    private $pdo;
    private $employeeController;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->employeeController = new EmployeeController($this->pdo);
    }

    public function testGetAllEmployees()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([]);

        $stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com'],
                ['id' => 2, 'name' => 'Jane Doe', 'email' => 'jane@example.com']
            ]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM employees')
            ->willReturn($stmt);

        $response = $this->employeeController->getAllEmployees();
        $this->assertIsArray($response);
        $this->assertCount(2, $response);
    }

    public function testGetEmployeeById()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([1]);

        $stmt->expects($this->once())
            ->method('fetch')
            ->willReturn(['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com']);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM employees WHERE id = ?')
            ->willReturn($stmt);

        $response = $this->employeeController->getEmployeeById(1);
        $this->assertIsArray($response);
        $this->assertEquals(1, $response['id']);
    }

    public function testCreateEmployee()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with(['John Doe', 'john@example.com']);

        $stmt->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO employees (name, email) VALUES (?, ?)')
            ->willReturn($stmt);

        $response = $this->employeeController->createEmployee(['name' => 'John Doe', 'email' => 'john@example.com']);
        $this->assertTrue($response);
    }

    public function testUpdateEmployee()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([1, 'John Doe', 'john@example.com']);

        $stmt->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE employees SET name = ?, email = ? WHERE id = ?')
            ->willReturn($stmt);

        $response = $this->employeeController->updateEmployee(1, ['name' => 'John Doe', 'email' => 'john@example.com']);
        $this->assertTrue($response);
    }

    public function testDeleteEmployee()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([1]);

        $stmt->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM employees WHERE id = ?')
            ->willReturn($stmt);

        $response = $this->employeeController->deleteEmployee(1);
        $this->assertTrue($response);
    }
}

class EmployeeController
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllEmployees()
    {
        $stmt = $this->pdo->prepare('SELECT * FROM employees');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getEmployeeById($id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM employees WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function createEmployee($data)
    {
        $stmt = $this->pdo->prepare('INSERT INTO employees (name, email) VALUES (?, ?)');
        $stmt->execute([$data['name'], $data['email']]);
        return $stmt->rowCount() > 0;
    }

    public function updateEmployee($id, $data)
    {
        $stmt = $this->pdo->prepare('UPDATE employees SET name = ?, email = ? WHERE id = ?');
        $stmt->execute([$data['name'], $data['email'], $id]);
        return $stmt->rowCount() > 0;
    }

    public function deleteEmployee($id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM employees WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }
}