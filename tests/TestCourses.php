<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Courses;

class TestCourses extends TestCase
{
    private $courses;
    private $request;
    private $response;

    protected function setUp(): void
    {
        $this->courses = new Courses();
        $this->request = $this->createMock(ServerRequestInterface::class);
        $this->response = $this->createMock(ResponseInterface::class);
    }

    public function testGetAllCourses()
    {
        $pdo = $this->createMock(\PDO::class);
        $stmt = $this->createMock(\PDOStatement::class);
        $pdo->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM courses')
            ->willReturn($stmt);

        $stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'Course 1'],
                ['id' => 2, 'name' => 'Course 2'],
            ]);

        $this->courses->setPdo($pdo);
        $result = $this->courses->getAllCourses();
        $this->assertIsArray($result);
        $this->assertCount(2, $result);
    }

    public function testGetCourseById()
    {
        $pdo = $this->createMock(\PDO::class);
        $stmt = $this->createMock(\PDOStatement::class);
        $pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM courses WHERE id = :id')
            ->willReturn($stmt);

        $stmt->expects($this->once())
            ->method('execute')
            ->with([':id' => 1]);

        $stmt->expects($this->once())
            ->method('fetch')
            ->willReturn(['id' => 1, 'name' => 'Course 1']);

        $this->courses->setPdo($pdo);
        $result = $this->courses->getCourseById(1);
        $this->assertIsArray($result);
        $this->assertEquals(1, $result['id']);
    }

    public function testCreateCourse()
    {
        $pdo = $this->createMock(\PDO::class);
        $stmt = $this->createMock(\PDOStatement::class);
        $pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO courses (name) VALUES (:name)')
            ->willReturn($stmt);

        $stmt->expects($this->once())
            ->method('execute')
            ->with([':name' => 'New Course']);

        $stmt->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $this->courses->setPdo($pdo);
        $result = $this->courses->createCourse('New Course');
        $this->assertTrue($result);
    }

    public function testUpdateCourse()
    {
        $pdo = $this->createMock(\PDO::class);
        $stmt = $this->createMock(\PDOStatement::class);
        $pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE courses SET name = :name WHERE id = :id')
            ->willReturn($stmt);

        $stmt->expects($this->once())
            ->method('execute')
            ->with([':id' => 1, ':name' => 'Updated Course']);

        $stmt->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $this->courses->setPdo($pdo);
        $result = $this->courses->updateCourse(1, 'Updated Course');
        $this->assertTrue($result);
    }

    public function testDeleteCourse()
    {
        $pdo = $this->createMock(\PDO::class);
        $stmt = $this->createMock(\PDOStatement::class);
        $pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM courses WHERE id = :id')
            ->willReturn($stmt);

        $stmt->expects($this->once())
            ->method('execute')
            ->with([':id' => 1]);

        $stmt->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $this->courses->setPdo($pdo);
        $result = $this->courses->deleteCourse(1);
        $this->assertTrue($result);
    }
}