<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\حجوزاتController;
use App\Repository\حجوزاتRepository;
use App\Entity\حجوزات;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use PHPUnit\Framework\MockObject\MockObject;

class Testحجوزات extends TestCase
{
    private $controller;
    private $repository;
    private $entityManager;
    private $pdo;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(حجوزاتRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->pdo = $this->createMock(\PDO::class);

        $this->entityManager->method('getRepository')->willReturn($this->repository);
        $this->entityManager->method('persist')->willReturn(null);
        $this->entityManager->method('flush')->willReturn(null);

        $this->repository->method('findAll')->willReturn([
            new حجوزات(),
            new حجوزات(),
        ]);

        $this->repository->method('find')->willReturn(new حجوزات());

        $this->repository->method('findOneBy')->willReturn(new حجوزات());

        $this->repository->method('remove')->willReturn(null);

        $this->repository->method('save')->willReturn(null);

        $this->controller = new حجوزاتController($this->entityManager);
    }

    public function testGetAll()
    {
        $response = $this->controller->getAll();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testGetOne()
    {
        $response = $this->controller->getOne(1);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testCreate()
    {
        $data = [
            'field1' => 'value1',
            'field2' => 'value2',
        ];

        $request = $this->createMock(Request::class);
        $request->method('request')->willReturn($data);

        $response = $this->controller->create($request);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testUpdate()
    {
        $data = [
            'field1' => 'value1',
            'field2' => 'value2',
        ];

        $request = $this->createMock(Request::class);
        $request->method('request')->willReturn($data);

        $response = $this->controller->update(1, $request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testDelete()
    {
        $response = $this->controller->delete(1);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}



// App\Controller\حجوزاتController.php

namespace App\Controller;

use App\Repository\حجوزاتRepository;
use App\Entity\حجوزات;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class حجوزاتController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getAll()
    {
        $repository = $this->entityManager->getRepository(حجوزات::class);
        $items = $repository->findAll();

        return new Response(json_encode($items), Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    public function getOne($id)
    {
        $repository = $this->entityManager->getRepository(حجوزات::class);
        $item = $repository->find($id);

        if (!$item) {
            throw new NotFoundHttpException('Item not found');
        }

        return new Response(json_encode($item), Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    public function create(Request $request)
    {
        $data = $request->request->all();

        $item = new حجوزات();
        $item->setField1($data['field1']);
        $item->setField2($data['field2']);

        $this->entityManager->persist($item);
        $this->entityManager->flush();

        return new Response('', Response::HTTP_CREATED, ['Content-Type' => 'application/json']);
    }

    public function update($id, Request $request)
    {
        $repository = $this->entityManager->getRepository(حجوزات::class);
        $item = $repository->find($id);

        if (!$item) {
            throw new NotFoundHttpException('Item not found');
        }

        $data = $request->request->all();
        $item->setField1($data['field1']);
        $item->setField2($data['field2']);

        $this->entityManager->persist($item);
        $this->entityManager->flush();

        return new Response('', Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    public function delete($id)
    {
        $repository = $this->entityManager->getRepository(حجوزات::class);
        $item = $repository->find($id);

        if (!$item) {
            throw new NotFoundHttpException('Item not found');
        }

        $this->entityManager->remove($item);
        $this->entityManager->flush();

        return new Response('', Response::HTTP_NO_CONTENT);
    }
}