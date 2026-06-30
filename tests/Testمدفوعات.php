<?php

namespace App\Tests\Controller;

use App\Controller\مدفوعاتController;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Testمدفوعات extends TestCase
{
    private $controller;
    private $pdoMock;

    protected function setUp(): void
    {
        $this->pdoMock = $this->createMock(PDO::class);
        $this->controller = new مدفوعاتController($this->pdoMock);
    }

    public function testGetAll()
    {
        $this->pdoMock->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM مدفوعات')
            ->willReturn($this->createMock(\PDOStatement::class));

        $response = $this->controller->getAll();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetOne()
    {
        $id = 1;
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM مدفوعات WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));
        $this->pdoMock->expects($this->once())
            ->method('bindParam')
            ->with(':id', $id);

        $response = $this->controller->getOne($id);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testCreate()
    {
        $data = ['name' => 'test', 'amount' => 100];
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO مدفوعات (name, amount) VALUES (:name, :amount)')
            ->willReturn($this->createMock(\PDOStatement::class));
        $this->pdoMock->expects($this->once())
            ->method('bindParam')
            ->with(':name', $data['name']);
        $this->pdoMock->expects($this->once())
            ->method('bindParam')
            ->with(':amount', $data['amount']);

        $response = $this->controller->create($data);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUpdate()
    {
        $id = 1;
        $data = ['name' => 'test', 'amount' => 100];
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('UPDATE مدفوعات SET name = :name, amount = :amount WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));
        $this->pdoMock->expects($this->once())
            ->method('bindParam')
            ->with(':name', $data['name']);
        $this->pdoMock->expects($this->once())
            ->method('bindParam')
            ->with(':amount', $data['amount']);
        $this->pdoMock->expects($this->once())
            ->method('bindParam')
            ->with(':id', $id);

        $response = $this->controller->update($id, $data);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDelete()
    {
        $id = 1;
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM مدفوعات WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));
        $this->pdoMock->expects($this->once())
            ->method('bindParam')
            ->with(':id', $id);

        $response = $this->controller->delete($id);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
}