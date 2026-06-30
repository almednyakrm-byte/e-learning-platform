<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Controller\ProfessorController;
use App\Repository\ProfessorRepository;
use App\Entity\Professor;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Paginator\PaginationInterface;

class TestProfessor extends TestCase
{
    private $controller;
    private $repository;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock('PDO');
        $this->repository = $this->createMock(ProfessorRepository::class);
        $this->controller = new ProfessorController($this->repository);

        $this->repository->method('getPDO')->willReturn($this->pdo);
    }

    public function testGetProfessors(): void
    {
        $this->pdo->method('query')->willReturn($this->createMock('PDOStatement'));
        $this->pdo->method('prepare')->willReturn($this->createMock('PDOStatement'));

        $request = new Request();
        $request->attributes->set('page', 1);

        $response = $this->controller->getProfessors($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetProfessor(): void
    {
        $professor = new Professor();
        $professor->setId(1);

        $this->pdo->method('prepare')->willReturn($this->createMock('PDOStatement'));
        $this->pdo->method('query')->willReturn($this->createMock('PDOStatement'));
        $this->repository->method('find')->willReturn($professor);

        $request = new Request();
        $request->attributes->set('id', 1);

        $response = $this->controller->getProfessor($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testCreateProfessor(): void
    {
        $professor = new Professor();
        $professor->setName('John Doe');
        $professor->setEmail('john@example.com');

        $this->pdo->method('prepare')->willReturn($this->createMock('PDOStatement'));
        $this->pdo->method('query')->willReturn($this->createMock('PDOStatement'));
        $this->repository->method('create')->willReturn($professor);

        $request = new Request();
        $request->request->set('name', 'John Doe');
        $request->request->set('email', 'john@example.com');

        $response = $this->controller->createProfessor($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUpdateProfessor(): void
    {
        $professor = new Professor();
        $professor->setId(1);
        $professor->setName('John Doe');
        $professor->setEmail('john@example.com');

        $this->pdo->method('prepare')->willReturn($this->createMock('PDOStatement'));
        $this->pdo->method('query')->willReturn($this->createMock('PDOStatement'));
        $this->repository->method('find')->willReturn($professor);
        $this->repository->method('update')->willReturn($professor);

        $request = new Request();
        $request->attributes->set('id', 1);
        $request->request->set('name', 'John Doe');
        $request->request->set('email', 'john@example.com');

        $response = $this->controller->updateProfessor($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDeleteProfessor(): void
    {
        $professor = new Professor();
        $professor->setId(1);

        $this->pdo->method('prepare')->willReturn($this->createMock('PDOStatement'));
        $this->pdo->method('query')->willReturn($this->createMock('PDOStatement'));
        $this->repository->method('find')->willReturn($professor);
        $this->repository->method('delete')->willReturn(null);

        $request = new Request();
        $request->attributes->set('id', 1);

        $response = $this->controller->deleteProfessor($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testGetProfessorNotFound(): void
    {
        $this->pdo->method('prepare')->willReturn($this->createMock('PDOStatement'));
        $this->pdo->method('query')->willReturn($this->createMock('PDOStatement'));
        $this->repository->method('find')->willReturn(null);

        $request = new Request();
        $request->attributes->set('id', 1);

        $this->expectException(NotFoundHttpException::class);

        $this->controller->getProfessor($request);
    }
}


This test file covers the following scenarios:

1.  `testGetProfessors()`: Tests the GET request for retrieving a list of professors.
2.  `testGetProfessor()`: Tests the GET request for retrieving a single professor by ID.
3.  `testCreateProfessor()`: Tests the POST request for creating a new professor.
4.  `testUpdateProfessor()`: Tests the PUT request for updating an existing professor.
5.  `testDeleteProfessor()`: Tests the DELETE request for deleting a professor.
6.  `testGetProfessorNotFound()`: Tests the scenario where a professor with the specified ID is not found.

Each test method uses the `createMock` method to create mock objects for the PDO and repository instances. The mock objects are configured to return specific values or throw exceptions when certain methods are called. This allows the tests to isolate the behavior of the controller and focus on the expected outcomes.

The test methods also use the `assertInstanceOf` and `assertEquals` methods to verify that the responses from the controller match the expected types and status codes.