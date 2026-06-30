<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\غرفController;
use App\Repository\غرفRepository;
use App\Entity\غرف;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;

class Testغرف extends TestCase
{
    private $controller;
    private $repository;
    private $router;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(غرفRepository::class);
        $this->router = $this->createMock(RouterInterface::class);
        $this->controller = new غرفController($this->repository, $this->router);
    }

    public function testGetAll(): void
    {
        $expectedResponse = ['data' => []];
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn($expectedResponse['data']);

        $response = $this->controller->getAll();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($expectedResponse, json_decode($response->getContent(), true));
    }

    public function testGetById(): void
    {
        $expectedResponse = ['data' => []];
        $id = 1;
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn($expectedResponse['data']);

        $response = $this->controller->getById($id);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($expectedResponse, json_decode($response->getContent(), true));
    }

    public function testCreate(): void
    {
        $expectedResponse = ['data' => []];
        $data = ['name' => 'test'];
        $this->repository->expects($this->once())
            ->method('save')
            ->with($data)
            ->willReturn($expectedResponse['data']);

        $request = new Request([], [], [], [], [], json_encode($data));
        $response = $this->controller->create($request);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals($expectedResponse, json_decode($response->getContent(), true));
    }

    public function testUpdate(): void
    {
        $expectedResponse = ['data' => []];
        $id = 1;
        $data = ['name' => 'test'];
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn($expectedResponse['data']);
        $this->repository->expects($this->once())
            ->method('save')
            ->with($data)
            ->willReturn($expectedResponse['data']);

        $request = new Request([], [], [], [], [], json_encode($data));
        $response = $this->controller->update($id, $request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($expectedResponse, json_decode($response->getContent(), true));
    }

    public function testDelete(): void
    {
        $id = 1;
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(new غرف());

        $response = $this->controller->delete($id);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteNotFound(): void
    {
        $id = 1;
        $this->expectException(NotFoundHttpException::class);
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(null);

        $this->controller->delete($id);
    }
}



// Entity\غرف.php

namespace App\Entity;

class غرف
{
    private $id;
    private $name;

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
}



// Controller\غرفController.php

namespace App\Controller;

use App\Repository\غرفRepository;
use App\Entity\غرف;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

class غرفController
{
    private $repository;
    private $router;

    public function __construct(غرفRepository $repository, RouterInterface $router)
    {
        $this->repository = $repository;
        $this->router = $router;
    }

    public function getAll(): Response
    {
        $data = $this->repository->findAll();
        return new Response(json_encode(['data' => $data]));
    }

    public function getById($id): Response
    {
        $data = $this->repository->find($id);
        if (!$data) {
            throw new NotFoundHttpException('Not found');
        }
        return new Response(json_encode(['data' => $data]));
    }

    public function create(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $this->repository->save($data);
        return new Response(json_encode(['data' => $data]), Response::HTTP_CREATED);
    }

    public function update($id, Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $entity = $this->repository->find($id);
        if (!$entity) {
            throw new NotFoundHttpException('Not found');
        }
        $entity->setName($data['name']);
        $this->repository->save($entity);
        return new Response(json_encode(['data' => $entity]));
    }

    public function delete($id): Response
    {
        $entity = $this->repository->find($id);
        if (!$entity) {
            throw new NotFoundHttpException('Not found');
        }
        $this->repository->remove($entity);
        return new Response('', Response::HTTP_NO_CONTENT);
    }
}



// Repository\غرفRepository.php

namespace App\Repository;

use App\Entity\غرف;
use Doctrine\ORM\EntityRepository;

class غرفRepository extends EntityRepository
{
    public function save($data): غرف
    {
        // Save logic here
        return new غرف();
    }

    public function find($id): ?غرف
    {
        // Find logic here
        return null;
    }

    public function findAll(): array
    {
        // Find all logic here
        return [];
    }

    public function remove($entity): void
    {
        // Remove logic here
    }
}