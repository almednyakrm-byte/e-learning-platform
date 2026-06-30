<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Controller\CoursesController;
use App\Repository\CoursesRepository;
use App\Entity\Courses;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class TestCourses extends TestCase
{
    private $coursesController;
    private $coursesRepository;
    private $entityManager;
    private $security;

    protected function setUp(): void
    {
        $this->coursesRepository = $this->createMock(CoursesRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->security = $this->createMock(Security::class);
        $this->coursesController = new CoursesController($this->coursesRepository, $this->entityManager, $this->security);
    }

    public function testGetCourses(): void
    {
        $courses = [
            new Courses('Course 1', 'Description 1'),
            new Courses('Course 2', 'Description 2'),
        ];

        $this->coursesRepository->expects($this->once())
            ->method('findAll')
            ->willReturn($courses);

        $response = $this->coursesController->getCourses();
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($courses), $response->getContent());
    }

    public function testGetCourse(): void
    {
        $course = new Courses('Course 1', 'Description 1');

        $this->coursesRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($course);

        $response = $this->coursesController->getCourse(1);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($course), $response->getContent());
    }

    public function testGetCourseNotFound(): void
    {
        $this->expectException(NotFoundHttpException::class);

        $this->coursesRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->coursesController->getCourse(1);
    }

    public function testCreateCourse(): void
    {
        $course = new Courses('Course 1', 'Description 1');
        $course->setId(1);

        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($course);

        $this->entityManager->expects($this->once())
            ->method('flush')
            ->willReturn(null);

        $request = new Request([], [], ['course' => ['name' => 'Course 1', 'description' => 'Description 1']]);
        $response = $this->coursesController->createCourse($request);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals(json_encode($course), $response->getContent());
    }

    public function testUpdateCourse(): void
    {
        $course = new Courses('Course 1', 'Description 1');
        $course->setId(1);

        $this->entityManager->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($course);

        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($course);

        $this->entityManager->expects($this->once())
            ->method('flush')
            ->willReturn(null);

        $request = new Request([], [], ['course' => ['name' => 'Course 1', 'description' => 'Description 1']]);
        $response = $this->coursesController->updateCourse(1, $request);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($course), $response->getContent());
    }

    public function testUpdateCourseNotFound(): void
    {
        $this->expectException(NotFoundHttpException::class);

        $course = new Courses('Course 1', 'Description 1');
        $course->setId(1);

        $this->entityManager->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $request = new Request([], [], ['course' => ['name' => 'Course 1', 'description' => 'Description 1']]);
        $this->coursesController->updateCourse(1, $request);
    }

    public function testDeleteCourse(): void
    {
        $course = new Courses('Course 1', 'Description 1');
        $course->setId(1);

        $this->entityManager->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($course);

        $this->entityManager->expects($this->once())
            ->method('remove')
            ->with($course);

        $this->entityManager->expects($this->once())
            ->method('flush')
            ->willReturn(null);

        $response = $this->coursesController->deleteCourse(1);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteCourseNotFound(): void
    {
        $this->expectException(NotFoundHttpException::class);

        $course = new Courses('Course 1', 'Description 1');
        $course->setId(1);

        $this->entityManager->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->coursesController->deleteCourse(1);
    }
}