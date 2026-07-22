<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\FasoulController;
use App\Repository\FasoulRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;

class TestFasoul extends TestCase
{
    private $controller;
    private $repository;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->repository = $this->createMock(FasoulRepository::class);
        $this->controller = new FasoulController($this->repository);
    }

    public function testGetAll(): void
    {
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([
                ['id' => 1, 'name' => 'فصول 1'],
                ['id' => 2, 'name' => 'فصول 2'],
            ]);

        $response = $this->controller->getAll();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals([
            ['id' => 1, 'name' => 'فصول 1'],
            ['id' => 2, 'name' => 'فصول 2'],
        ], json_decode($response->getContent(), true));
    }

    public function testGetById(): void
    {
        $this->repository->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn(['id' => 1, 'name' => 'فصول 1']);

        $response = $this->controller->getById(1);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(['id' => 1, 'name' => 'فصول 1'], json_decode($response->getContent(), true));
    }

    public function testCreate(): void
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO fasoul (name) VALUES (:name)')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->pdo->expects($this->once())
            ->method('execute')
            ->with(['name' => 'فصول 1']);

        $response = $this->controller->create(['name' => 'فصول 1']);
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testUpdate(): void
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE fasoul SET name = :name WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->pdo->expects($this->once())
            ->method('execute')
            ->with(['name' => 'فصول 1', 'id' => 1]);

        $response = $this->controller->update(1, ['name' => 'فصول 1']);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testDelete(): void
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM fasoul WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->pdo->expects($this->once())
            ->method('execute')
            ->with(['id' => 1]);

        $response = $this->controller->delete(1);
        $this->assertEquals(200, $response->getStatusCode());
    }
}