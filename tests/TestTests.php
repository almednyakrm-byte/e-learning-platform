<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use PDOStatement;

class TestTests extends TestCase
{
    private $pdo;
    private $testsController;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->testsController = new TestsController($this->pdo);
    }

    public function testGetAllTests()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([]);

        $stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'Test 1'],
                ['id' => 2, 'name' => 'Test 2'],
            ]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM tests')
            ->willReturn($stmt);

        $response = $this->testsController->getAllTests();
        $this->assertIsArray($response);
        $this->assertCount(2, $response);
    }

    public function testGetTestById()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([1]);

        $stmt->expects($this->once())
            ->method('fetch')
            ->willReturn(['id' => 1, 'name' => 'Test 1']);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM tests WHERE id = ?')
            ->willReturn($stmt);

        $response = $this->testsController->getTestById(1);
        $this->assertIsArray($response);
        $this->assertEquals(1, $response['id']);
    }

    public function testCreateTest()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with(['Test 1']);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO tests (name) VALUES (?)')
            ->willReturn($stmt);

        $response = $this->testsController->createTest(['name' => 'Test 1']);
        $this->assertTrue($response);
    }

    public function testUpdateTest()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with(['Test 1', 1]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE tests SET name = ? WHERE id = ?')
            ->willReturn($stmt);

        $response = $this->testsController->updateTest(1, ['name' => 'Test 1']);
        $this->assertTrue($response);
    }

    public function testDeleteTest()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([1]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM tests WHERE id = ?')
            ->willReturn($stmt);

        $response = $this->testsController->deleteTest(1);
        $this->assertTrue($response);
    }
}

class TestsController
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllTests()
    {
        $stmt = $this->pdo->prepare('SELECT * FROM tests');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getTestById($id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM tests WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function createTest($data)
    {
        $stmt = $this->pdo->prepare('INSERT INTO tests (name) VALUES (?)');
        return $stmt->execute([$data['name']]);
    }

    public function updateTest($id, $data)
    {
        $stmt = $this->pdo->prepare('UPDATE tests SET name = ? WHERE id = ?');
        return $stmt->execute([$data['name'], $id]);
    }

    public function deleteTest($id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM tests WHERE id = ?');
        return $stmt->execute([$id]);
    }
}