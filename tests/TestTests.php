<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use PDO;
use PDOStatement;

class TestTests extends TestCase
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

        $tests = [];
        $this->pdo->query('SELECT * FROM tests')->fetchAll(PDO::FETCH_ASSOC);
        $this->assertIsArray($tests);
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

        $test = [];
        $stmt = $this->pdo->prepare('SELECT * FROM tests WHERE id = ?');
        $stmt->execute([1]);
        $test = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->assertIsArray($test);
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

        $this->request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['name' => 'Test 1']);

        $stmt = $this->pdo->prepare('INSERT INTO tests (name) VALUES (?)');
        $stmt->execute(['Test 1']);
        $this->assertTrue(true);
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

        $this->request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['name' => 'Test 1']);

        $stmt = $this->pdo->prepare('UPDATE tests SET name = ? WHERE id = ?');
        $stmt->execute(['Test 1', 1]);
        $this->assertTrue(true);
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

        $stmt = $this->pdo->prepare('DELETE FROM tests WHERE id = ?');
        $stmt->execute([1]);
        $this->assertTrue(true);
    }
}