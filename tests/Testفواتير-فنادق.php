<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\FatouraFannadqController;
use App\Repository\FatouraFannadqRepository;
use App\Entity\FatouraFannadq;
use App\Service\FatouraFannadqService;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class Testفواتير_فنادق extends TestCase
{
    private $controller;
    private $repository;
    private $service;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock('PDO');
        $this->repository = $this->createMock(FatouraFannadqRepository::class);
        $this->service = $this->createMock(FatouraFannadqService::class);
        $this->controller = new FatouraFannadqController($this->repository, $this->service);

        $this->pdo->expects($this->any())
            ->method('prepare')
            ->willReturn($this->createMock('PDOStatement'));

        $this->repository->expects($this->any())
            ->method('findAll')
            ->willReturn([]);

        $this->repository->expects($this->any())
            ->method('find')
            ->willReturn(new FatouraFannadq());

        $this->repository->expects($this->any())
            ->method('save')
            ->willReturn(new FatouraFannadq());

        $this->repository->expects($this->any())
            ->method('remove')
            ->willReturn(new FatouraFannadq());
    }

    public function testGetAll(): void
    {
        $request = new Request();
        $request->setMethod('GET');

        $response = $this->controller->getAll($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetOne(): void
    {
        $request = new Request();
        $request->setMethod('GET');
        $request->setRequestUri('/api/fatoura-fannadq/1');

        $response = $this->controller->getOne($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testCreate(): void
    {
        $request = new Request();
        $request->setMethod('POST');
        $request->request->set('name', 'Fatoura Fannadq');
        $request->request->set('description', 'Description');

        $response = $this->controller->create($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUpdate(): void
    {
        $request = new Request();
        $request->setMethod('PUT');
        $request->request->set('name', 'Fatoura Fannadq Updated');
        $request->request->set('description', 'Description Updated');
        $request->setRequestUri('/api/fatoura-fannadq/1');

        $response = $this->controller->update($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDelete(): void
    {
        $request = new Request();
        $request->setMethod('DELETE');
        $request->setRequestUri('/api/fatoura-fannadq/1');

        $response = $this->controller->delete($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}