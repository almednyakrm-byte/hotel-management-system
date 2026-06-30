<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\حجز_غرفController;
use App\Repository\حجز_غرفRepository;
use App\Entity\حجز_غرف;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class Testحجز_غرف extends TestCase
{
    private $controller;
    private $repository;
    private $entityManager;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(حجز_غرفRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->controller = new حجز_غرفController($this->repository, $this->entityManager);
    }

    public function testGetAll(): void
    {
        $expectedResponse = new JsonResponse(['data' => []]);
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([]);
        $response = $this->controller->getAll();
        $this->assertEquals($expectedResponse, $response);
    }

    public function testGetById(): void
    {
        $expectedResponse = new JsonResponse(['data' => []]);
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(new حجز_غرف());
        $response = $this->controller->getById(1);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testCreate(): void
    {
        $expectedResponse = new JsonResponse(['data' => []]);
        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($this->isInstanceOf(حجز_غرف::class));
        $this->entityManager->expects($this->once())
            ->method('flush');
        $request = new Request([], [], ['json' => ['name' => 'test']]);
        $response = $this->controller->create($request);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testUpdate(): void
    {
        $expectedResponse = new JsonResponse(['data' => []]);
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(new حجز_غرف());
        $this->entityManager->expects($this->once())
            ->method('flush');
        $request = new Request([], [], ['json' => ['name' => 'test']]);
        $response = $this->controller->update(1, $request);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testDelete(): void
    {
        $expectedResponse = new JsonResponse(['data' => []]);
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(new حجز_غرف());
        $this->entityManager->expects($this->once())
            ->method('remove')
            ->with($this->isInstanceOf(حجز_غرف::class));
        $this->entityManager->expects($this->once())
            ->method('flush');
        $response = $this->controller->delete(1);
        $this->assertEquals($expectedResponse, $response);
    }
}



// حجز_غرفController.php
namespace App\Controller;

use App\Repository\حجز_غرفRepository;
use App\Entity\حجز_غرف;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class حجز_غرفController
{
    private $repository;
    private $entityManager;

    public function __construct(حجز_غرفRepository $repository, EntityManagerInterface $entityManager)
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }

    public function getAll(): Response
    {
        $data = $this->repository->findAll();
        return new JsonResponse(['data' => $data]);
    }

    public function getById(int $id): Response
    {
        $data = $this->repository->find($id);
        return new JsonResponse(['data' => $data]);
    }

    public function create(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $entity = new حجز_غرف();
        $entity->setName($data['name']);
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
        return new JsonResponse(['data' => $entity]);
    }

    public function update(int $id, Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $entity = $this->repository->find($id);
        $entity->setName($data['name']);
        $this->entityManager->flush();
        return new JsonResponse(['data' => $entity]);
    }

    public function delete(int $id): Response
    {
        $entity = $this->repository->find($id);
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
        return new JsonResponse(['data' => []]);
    }
}



// حجز_غرفRepository.php
namespace App\Repository;

use App\Entity\حجز_غرف;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class حجز_غرفRepository extends EntityRepository
{
    public function findAll(): array
    {
        return [];
    }

    public function find(int $id): حجز_غرف
    {
        return new حجز_غرف();
    }
}