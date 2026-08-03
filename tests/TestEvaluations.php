<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use PDOStatement;

class TestEvaluations extends TestCase
{
    private $pdo;
    private $evaluation;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->evaluation = new Evaluations($this->pdo);
    }

    public function testGetEvaluations()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([]);

        $stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'Evaluation 1'],
                ['id' => 2, 'name' => 'Evaluation 2'],
            ]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM evaluations')
            ->willReturn($stmt);

        $result = $this->evaluation->getEvaluations();
        $this->assertCount(2, $result);
    }

    public function testGetEvaluationById()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([1]);

        $stmt->expects($this->once())
            ->method('fetch')
            ->willReturn(['id' => 1, 'name' => 'Evaluation 1']);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM evaluations WHERE id = ?')
            ->willReturn($stmt);

        $result = $this->evaluation->getEvaluationById(1);
        $this->assertEquals(1, $result['id']);
    }

    public function testCreateEvaluation()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with(['name' => 'New Evaluation']);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO evaluations (name) VALUES (?)')
            ->willReturn($stmt);

        $result = $this->evaluation->createEvaluation(['name' => 'New Evaluation']);
        $this->assertTrue($result);
    }

    public function testUpdateEvaluation()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([1, 'Updated Evaluation']);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE evaluations SET name = ? WHERE id = ?')
            ->willReturn($stmt);

        $result = $this->evaluation->updateEvaluation(1, ['name' => 'Updated Evaluation']);
        $this->assertTrue($result);
    }

    public function testDeleteEvaluation()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([1]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM evaluations WHERE id = ?')
            ->willReturn($stmt);

        $result = $this->evaluation->deleteEvaluation(1);
        $this->assertTrue($result);
    }
}

class Evaluations
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getEvaluations()
    {
        $stmt = $this->pdo->prepare('SELECT * FROM evaluations');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getEvaluationById($id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM evaluations WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function createEvaluation($data)
    {
        $stmt = $this->pdo->prepare('INSERT INTO evaluations (name) VALUES (?)');
        return $stmt->execute([$data['name']]);
    }

    public function updateEvaluation($id, $data)
    {
        $stmt = $this->pdo->prepare('UPDATE evaluations SET name = ? WHERE id = ?');
        return $stmt->execute([$data['name'], $id]);
    }

    public function deleteEvaluation($id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM evaluations WHERE id = ?');
        return $stmt->execute([$id]);
    }
}