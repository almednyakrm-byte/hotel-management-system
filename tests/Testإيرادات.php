<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\Controller\إيراداتController;
use App\Repository\إيراداتRepository;
use App\Entity\إيرادات;

class Testإيرادات extends TestCase
{
    private $controller;
    private $repository;
    private $router;
    private $tokenStorage;
    private $pdo;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(إيراداتRepository::class);
        $this->router = $this->createMock(RouterInterface::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->pdo = $this->createMock(\PDO::class);

        $this->controller = new إيراداتController($this->repository, $this->router, $this->tokenStorage, $this->pdo);
    }

    public function testGetAll()
    {
        $expectedResponse = new Response(json_encode([new إيرادات()]));

        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([new إيرادات()]);

        $response = $this->controller->getAll();

        $this->assertEquals($expectedResponse, $response);
    }

    public function testGetOne()
    {
        $expectedResponse = new Response(json_encode(new إيرادات()));

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(new إيرادات());

        $response = $this->controller->getOne(1);

        $this->assertEquals($expectedResponse, $response);
    }

    public function testCreate()
    {
        $expectedResponse = new Response(json_encode(new إيرادات()));

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO إيرادات (name, amount) VALUES (:name, :amount)')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->pdo->expects($this->once())
            ->method('execute')
            ->with(['name' => 'إيرادات', 'amount' => 100]);

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(new إيرادات());

        $request = new Request([], [], ['name' => 'إيرادات', 'amount' => 100]);
        $response = $this->controller->create($request);

        $this->assertEquals($expectedResponse, $response);
    }

    public function testUpdate()
    {
        $expectedResponse = new Response(json_encode(new إيرادات()));

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE إيرادات SET name = :name, amount = :amount WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->pdo->expects($this->once())
            ->method('execute')
            ->with(['name' => 'إيرادات', 'amount' => 100, 'id' => 1]);

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(new إيرادات());

        $request = new Request([], [], ['name' => 'إيرادات', 'amount' => 100]);
        $response = $this->controller->update(1, $request);

        $this->assertEquals($expectedResponse, $response);
    }

    public function testDelete()
    {
        $expectedResponse = new Response('', Response::HTTP_NO_CONTENT);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM إيرادات WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->pdo->expects($this->once())
            ->method('execute')
            ->with(['id' => 1]);

        $response = $this->controller->delete(1);

        $this->assertEquals($expectedResponse, $response);
    }
}


This test file covers the CRUD operations for the 'إيرادات' module. It uses mocked PDO statements to simulate database interactions. The tests verify that the controller returns the expected responses for GET, POST, PUT, and DELETE requests.