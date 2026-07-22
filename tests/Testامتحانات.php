<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Controller\ExamController;
use App\Repository\ExamRepository;
use App\Service\ExamService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Tests\MockRequest;

class Testامتحانات extends TestCase
{
    private $examController;
    private $examRepository;
    private $examService;
    private $entityManager;

    protected function setUp(): void
    {
        $this->examRepository = $this->createMock(ExamRepository::class);
        $this->examService = $this->createMock(ExamService::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->examController = new ExamController($this->examRepository, $this->examService, $this->entityManager);
    }

    public function testGetExams()
    {
        $this->examRepository->expects($this->once())
            ->method('findAll')
            ->willReturn([
                ['id' => 1, 'name' => 'Exam 1'],
                ['id' => 2, 'name' => 'Exam 2'],
            ]);

        $request = new Request();
        $response = $this->examController->getExams($request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testCreateExam()
    {
        $examData = ['name' => 'New Exam'];
        $this->examService->expects($this->once())
            ->method('createExam')
            ->with($examData)
            ->willReturn(['id' => 3, 'name' => 'New Exam']);

        $request = new Request([], [], ['name' => 'New Exam']);
        $response = $this->examController->createExam($request);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testUpdateExam()
    {
        $examId = 1;
        $examData = ['name' => 'Updated Exam'];
        $this->examService->expects($this->once())
            ->method('updateExam')
            ->with($examId, $examData)
            ->willReturn(['id' => 1, 'name' => 'Updated Exam']);

        $request = new Request([], [], ['name' => 'Updated Exam']);
        $response = $this->examController->updateExam($request, $examId);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testDeleteExam()
    {
        $examId = 1;
        $this->examRepository->expects($this->once())
            ->method('deleteExam')
            ->with($examId);

        $request = new Request();
        $response = $this->examController->deleteExam($request, $examId);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}


This test file covers the CRUD API operations on the 'امتحانات' module. It uses mocked PDO statements to simulate database interactions. The tests verify the expected behavior of the `ExamController` class for GET, POST, PUT, and DELETE requests.