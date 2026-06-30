<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\طلابController;
use App\Repository\طلابRepository;
use App\Entity\طلاب;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class Testطلاب extends TestCase
{
    private $controller;
    private $repository;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(طلابRepository::class);
        $this->controller = new طلابController($this->repository);
    }

    public function testGetAll()
    {
        $expectedResponse = new JsonResponse(['data' => []]);
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([]);

        $response = $this->controller->getAll();
        $this->assertEquals($expectedResponse, $response);
    }

    public function testGetById()
    {
        $expectedResponse = new JsonResponse(['data' => []]);
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(new طلاب());

        $response = $this->controller->getById(1);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testCreate()
    {
        $expectedResponse = new JsonResponse(['data' => []]);
        $this->repository->expects($this->once())
            ->method('save')
            ->with(new طلاب());

        $request = new Request([], [], ['json' => ['name' => 'test']]);
        $response = $this->controller->create($request);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testUpdate()
    {
        $expectedResponse = new JsonResponse(['data' => []]);
        $this->repository->expects($this->once())
            ->method('update')
            ->with(new طلاب());

        $request = new Request([], [], ['json' => ['name' => 'test']]);
        $response = $this->controller->update(1, $request);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testDelete()
    {
        $expectedResponse = new JsonResponse(['data' => []]);
        $this->repository->expects($this->once())
            ->method('delete')
            ->with(1);

        $response = $this->controller->delete(1);
        $this->assertEquals($expectedResponse, $response);
    }
}



// App\Controller\طلابController.php

namespace App\Controller;

use App\Repository\طلابRepository;
use App\Entity\طلاب;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class طلابController
{
    private $repository;

    public function __construct(طلابRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll()
    {
        return new JsonResponse(['data' => $this->repository->findAll()]);
    }

    public function getById(int $id)
    {
        return new JsonResponse(['data' => $this->repository->find($id)]);
    }

    public function create(Request $request)
    {
        $student = new طلاب();
        $student->setName($request->get('name'));
        $this->repository->save($student);
        return new JsonResponse(['data' => $student]);
    }

    public function update(int $id, Request $request)
    {
        $student = $this->repository->find($id);
        $student->setName($request->get('name'));
        $this->repository->update($student);
        return new JsonResponse(['data' => $student]);
    }

    public function delete(int $id)
    {
        $this->repository->delete($id);
        return new JsonResponse(['data' => []]);
    }
}



// App\Repository\طلابRepository.php

namespace App\Repository;

use App\Entity\طلاب;

interface طلابRepository
{
    public function findAll(): array;
    public function find(int $id): ?طلاب;
    public function save(طلاب $student): void;
    public function update(طلاب $student): void;
    public function delete(int $id): void;
}



// App\Entity\طلاب.php

namespace App\Entity;

class طلاب
{
    private $id;
    private $name;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }
}