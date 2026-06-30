<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Paginator\PaginatorInterface;
use Symfony\Component\Paginator\PaginationInterface;
use App\Repository\غرف_فنادقRepository;
use App\Entity\غرف_فنادق;
use App\Controller\غرف_فنادقController;

class Testغرف_فنادق extends TestCase
{
    private $controller;
    private $repository;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock('PDO');
        $this->repository = $this->createMock('App\Repository\غرف_فنادقRepository');
        $this->controller = new غرف_فنادقController($this->repository);
    }

    public function testGetAll(): void
    {
        $paginator = $this->createMock('Symfony\Component\Paginator\PaginatorInterface');
        $pagination = $this->createMock('Symfony\Component\Paginator\PaginationInterface');
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn($pagination);
        $response = $this->controller->getAll();
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetOne(): void
    {
        $entity = new غرف_فنادق();
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($entity);
        $response = $this->controller->getOne(1);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetOneNotFound(): void
    {
        $this->expectException(NotFoundHttpException::class);
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);
        $this->controller->getOne(1);
    }

    public function testCreate(): void
    {
        $entity = new غرف_فنادق();
        $this->repository->expects($this->once())
            ->method('create')
            ->with($entity)
            ->willReturn($entity);
        $response = $this->controller->create($entity);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUpdate(): void
    {
        $entity = new غرف_فنادق();
        $this->repository->expects($this->once())
            ->method('update')
            ->with($entity)
            ->willReturn($entity);
        $response = $this->controller->update(1, $entity);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testUpdateNotFound(): void
    {
        $entity = new غرف_فنادق();
        $this->expectException(NotFoundHttpException::class);
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);
        $this->controller->update(1, $entity);
    }

    public function testDelete(): void
    {
        $entity = new غرف_فنادق();
        $this->repository->expects($this->once())
            ->method('delete')
            ->with($entity)
            ->willReturn($entity);
        $response = $this->controller->delete(1);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDeleteNotFound(): void
    {
        $this->expectException(NotFoundHttpException::class);
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);
        $this->controller->delete(1);
    }
}