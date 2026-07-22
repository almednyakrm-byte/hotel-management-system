<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\EmtezatController;
use App\Repository\EmtezatRepository;
use App\Service\EmtezatService;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TestEmtezat extends TestCase
{
    private $emtezatController;
    private $emtezatRepository;
    private $emtezatService;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock('PDO');
        $this->emtezatRepository = $this->createMock(EmtezatRepository::class);
        $this->emtezatService = $this->createMock(EmtezatService::class);
        $this->emtezatController = new EmtezatController($this->emtezatRepository, $this->emtezatService, $this->pdo);
    }

    public function testGetEmtezats()
    {
        $this->emtezatRepository->expects($this->once())
            ->method('findAll')
            ->willReturn([
                ['id' => 1, 'name' => 'Emtezat 1'],
                ['id' => 2, 'name' => 'Emtezat 2'],
            ]);

        $request = new Request();
        $response = $this->emtezatController->getEmtezats($request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testGetEmtezat()
    {
        $this->emtezatRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(['id' => 1, 'name' => 'Emtezat 1']);

        $request = new Request();
        $response = $this->emtezatController->getEmtezat($request, 1);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testGetEmtezatNotFound()
    {
        $this->emtezatRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $request = new Request();
        $this->expectException(NotFoundHttpException::class);
        $this->emtezatController->getEmtezat($request, 1);
    }

    public function testCreateEmtezat()
    {
        $this->emtezatService->expects($this->once())
            ->method('createEmtezat')
            ->with(['name' => 'Emtezat 1'])
            ->willReturn(['id' => 1, 'name' => 'Emtezat 1']);

        $request = new Request([], [], ['name' => 'Emtezat 1']);
        $response = $this->emtezatController->createEmtezat($request);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testUpdateEmtezat()
    {
        $this->emtezatRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(['id' => 1, 'name' => 'Emtezat 1']);

        $this->emtezatService->expects($this->once())
            ->method('updateEmtezat')
            ->with(1, ['name' => 'Emtezat 2'])
            ->willReturn(['id' => 1, 'name' => 'Emtezat 2']);

        $request = new Request([], [], ['name' => 'Emtezat 2']);
        $response = $this->emtezatController->updateEmtezat($request, 1);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testUpdateEmtezatNotFound()
    {
        $this->emtezatRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $request = new Request([], [], ['name' => 'Emtezat 1']);
        $this->expectException(NotFoundHttpException::class);
        $this->emtezatController->updateEmtezat($request, 1);
    }

    public function testDeleteEmtezat()
    {
        $this->emtezatRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(['id' => 1, 'name' => 'Emtezat 1']);

        $this->emtezatService->expects($this->once())
            ->method('deleteEmtezat')
            ->with(1);

        $request = new Request();
        $response = $this->emtezatController->deleteEmtezat($request, 1);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteEmtezatNotFound()
    {
        $this->emtezatRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $request = new Request();
        $this->expectException(NotFoundHttpException::class);
        $this->emtezatController->deleteEmtezat($request, 1);
    }
}


This test file covers the following scenarios:

- `testGetEmtezats`: Tests the `getEmtezats` method to retrieve all emtezats.
- `testGetEmtezat`: Tests the `getEmtezat` method to retrieve a single emtezat by ID.
- `testGetEmtezatNotFound`: Tests the `getEmtezat` method when the emtezat is not found.
- `testCreateEmtezat`: Tests the `createEmtezat` method to create a new emtezat.
- `testUpdateEmtezat`: Tests the `updateEmtezat` method to update an existing emtezat.
- `testUpdateEmtezatNotFound`: Tests the `updateEmtezat` method when the emtezat is not found.
- `testDeleteEmtezat`: Tests the `deleteEmtezat` method to delete an existing emtezat.
- `testDeleteEmtezatNotFound`: Tests the `deleteEmtezat` method when the emtezat is not found.

Note that this test file assumes that the `EmtezatController`, `EmtezatRepository`, and `EmtezatService` classes are already implemented and available in the test environment.