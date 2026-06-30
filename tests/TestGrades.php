<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\GradesController;
use App\Repository\GradesRepository;
use App\Service\GradesService;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use PDOStatement;

class TestGrades extends TestCase
{
    private $controller;
    private $repository;
    private $service;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->repository = $this->createMock(GradesRepository::class);
        $this->service = $this->createMock(GradesService::class);
        $this->controller = new GradesController($this->repository, $this->service);

        $this->pdo->method('prepare')->willReturn($this->createMock(PDOStatement::class));
    }

    public function testGetGrades()
    {
        $this->repository->method('getAllGrades')->willReturn([
            ['id' => 1, 'name' => 'Grade 1'],
            ['id' => 2, 'name' => 'Grade 2'],
        ]);

        $response = $this->controller->getGrades();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals([
            ['id' => 1, 'name' => 'Grade 1'],
            ['id' => 2, 'name' => 'Grade 2'],
        ], json_decode($response->getBody()->getContents(), true));
    }

    public function testPostGrade()
    {
        $this->service->method('createGrade')->willReturn(['id' => 1, 'name' => 'Grade 1']);

        $response = $this->controller->postGrade(['name' => 'Grade 1']);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals(['id' => 1, 'name' => 'Grade 1'], json_decode($response->getBody()->getContents(), true));
    }

    public function testPutGrade()
    {
        $this->service->method('updateGrade')->willReturn(['id' => 1, 'name' => 'Grade 1']);

        $response = $this->controller->putGrade(1, ['name' => 'Grade 1']);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(['id' => 1, 'name' => 'Grade 1'], json_decode($response->getBody()->getContents(), true));
    }

    public function testDeleteGrade()
    {
        $this->service->method('deleteGrade')->willReturn(true);

        $response = $this->controller->deleteGrade(1);

        $this->assertEquals(204, $response->getStatusCode());
    }
}