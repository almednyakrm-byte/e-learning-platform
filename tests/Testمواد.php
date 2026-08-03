<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\MaterialsController;
use App\Repository\MaterialsRepository;
use App\Entity\Materials;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class Testمواد extends TestCase
{
    private $materialsController;
    private $materialsRepository;

    protected function setUp(): void
    {
        $this->materialsRepository = $this->createMock(MaterialsRepository::class);
        $this->materialsController = new MaterialsController($this->materialsRepository);
    }

    public function testGetMaterials(): void
    {
        $materials = [
            new Materials(1, 'Material 1'),
            new Materials(2, 'Material 2'),
        ];

        $this->materialsRepository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn($materials);

        $response = $this->materialsController->getMaterials(new Request());

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($materials), $response->getContent());
    }

    public function testPostMaterials(): void
    {
        $materials = new Materials(1, 'Material 1');
        $request = new Request();
        $request->request->set('name', 'Material 1');

        $this->materialsRepository
            ->expects($this->once())
            ->method('save')
            ->with($materials);

        $response = $this->materialsController->postMaterials($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testPutMaterials(): void
    {
        $materials = new Materials(1, 'Material 1');
        $request = new Request();
        $request->request->set('name', 'Material 2');

        $this->materialsRepository
            ->expects($this->once())
            ->method('update')
            ->with($materials);

        $response = $this->materialsController->putMaterials($request, 1);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDeleteMaterials(): void
    {
        $this->materialsRepository
            ->expects($this->once())
            ->method('delete')
            ->with(1);

        $response = $this->materialsController->deleteMaterials(1);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}