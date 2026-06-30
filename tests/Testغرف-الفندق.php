<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\غرف_الفندقController;
use App\Repository\غرف_الفندقRepository;
use App\Entity\غرف_الفندق;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class Testغرف_الفندق extends TestCase
{
    private $controller;
    private $repository;
    private $entityManager;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(غرف_الفندقRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->entityManager->method('getRepository')->willReturn($this->repository);
        $this->controller = new غرف_الفندقController($this->entityManager);
    }

    public function testGetAll()
    {
        $expectedResponse = new JsonResponse(['data' => []]);
        $this->repository->method('findAll')->willReturn([]);
        $response = $this->controller->getAll();
        $this->assertEquals($expectedResponse, $response);
    }

    public function testGetById()
    {
        $expectedResponse = new JsonResponse(['data' => []]);
        $this->repository->method('find')->willReturn(null);
        $response = $this->controller->getById(1);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testCreate()
    {
        $expectedResponse = new JsonResponse(['data' => []]);
        $this->entityManager->method('persist')->willReturn(null);
        $this->entityManager->method('flush')->willReturn(null);
        $request = new Request([], [], ['json' => ['name' => 'test']]);
        $response = $this->controller->create($request);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testUpdate()
    {
        $expectedResponse = new JsonResponse(['data' => []]);
        $this->entityManager->method('persist')->willReturn(null);
        $this->entityManager->method('flush')->willReturn(null);
        $request = new Request([], [], ['json' => ['name' => 'test']]);
        $response = $this->controller->update(1, $request);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testDelete()
    {
        $expectedResponse = new JsonResponse(['data' => []]);
        $this->entityManager->method('remove')->willReturn(null);
        $this->entityManager->method('flush')->willReturn(null);
        $response = $this->controller->delete(1);
        $this->assertEquals($expectedResponse, $response);
    }
}



// App\Controller\غرف_الفندقController.php

namespace App\Controller;

use App\Repository\غرف_الفندقRepository;
use App\Entity\غرف_الفندق;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class غرف_الفندقController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getAll()
    {
        $repository = $this->entityManager->getRepository(غرف_الفندق::class);
        $data = $repository->findAll();
        return new JsonResponse(['data' => $data]);
    }

    public function getById($id)
    {
        $repository = $this->entityManager->getRepository(غرف_الفندق::class);
        $data = $repository->find($id);
        if (!$data) {
            return new JsonResponse(['data' => []]);
        }
        return new JsonResponse(['data' => $data]);
    }

    public function create(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $entity = new غرف_الفندق();
        $entity->setName($data['name']);
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
        return new JsonResponse(['data' => []]);
    }

    public function update($id, Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $entity = $this->entityManager->getRepository(غرف_الفندق::class)->find($id);
        if (!$entity) {
            return new JsonResponse(['data' => []]);
        }
        $entity->setName($data['name']);
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
        return new JsonResponse(['data' => []]);
    }

    public function delete($id)
    {
        $entity = $this->entityManager->getRepository(غرف_الفندق::class)->find($id);
        if (!$entity) {
            return new JsonResponse(['data' => []]);
        }
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
        return new JsonResponse(['data' => []]);
    }
}



// App\Repository\غرف_الفندقRepository.php

namespace App\Repository;

use App\Entity\غرف_الفندق;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class غرف_الفندقRepository extends EntityRepository
{
    public function findAll()
    {
        return $this->findBy([]);
    }

    public function find($id)
    {
        return $this->find($id);
    }
}



// App\Entity\غرف_الفندق.php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class غرف_الفندق
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
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