<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use PDOStatement;

class TestStudents extends TestCase
{
    private $pdo;
    private $stmt;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->stmt = $this->createMock(PDOStatement::class);
    }

    public function testGetStudents()
    {
        $this->pdo->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM students')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'John Doe'],
                ['id' => 2, 'name' => 'Jane Doe']
            ]);

        $students = $this->pdo->query('SELECT * FROM students')->fetchAll();
        $this->assertCount(2, $students);
    }

    public function testCreateStudent()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO students (name) VALUES (:name)')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':name', 'John Doe');

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $stmt = $this->pdo->prepare('INSERT INTO students (name) VALUES (:name)');
        $stmt->bindParam(':name', 'John Doe');
        $this->assertTrue($stmt->execute());
    }

    public function testUpdateStudent()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE students SET name = :name WHERE id = :id')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':name', 'Jane Doe');

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':id', 1);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $stmt = $this->pdo->prepare('UPDATE students SET name = :name WHERE id = :id');
        $stmt->bindParam(':name', 'Jane Doe');
        $stmt->bindParam(':id', 1);
        $this->assertTrue($stmt->execute());
    }

    public function testDeleteStudent()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM students WHERE id = :id')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':id', 1);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $stmt = $this->pdo->prepare('DELETE FROM students WHERE id = :id');
        $stmt->bindParam(':id', 1);
        $this->assertTrue($stmt->execute());
    }
}