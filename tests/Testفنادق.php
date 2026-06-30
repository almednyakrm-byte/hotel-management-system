<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class Testفنادق extends TestCase
{
    private $request;
    private $response;
    private $stream;
    private $pdo;

    protected function setUp(): void
    {
        $this->request = $this->createMock(ServerRequestInterface::class);
        $this->response = $this->createMock(ResponseInterface::class);
        $this->stream = $this->createMock(StreamInterface::class);
        $this->pdo = $this->createMock(\PDO::class);
    }

    public function testGetفنادق()
    {
        $this->pdo->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM فنادق')
            ->willReturn($this->createMock(\PDOStatement::class));

        $controller = new فنادقController($this->pdo);
        $response = $controller->getفنادق($this->request, $this->response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testPostفنادق()
    {
        $data = ['name' => 'Test Hotel', 'address' => 'Test Address'];
        $this->request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn($data);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO فنادق (name, address) VALUES (:name, :address)')
            ->willReturn($this->createMock(\PDOStatement::class));

        $controller = new فنادقController($this->pdo);
        $response = $controller->postفنادق($this->request, $this->response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testPutفنادق()
    {
        $data = ['name' => 'Test Hotel', 'address' => 'Test Address'];
        $this->request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn($data);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE فنادق SET name = :name, address = :address WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $controller = new فنادقController($this->pdo);
        $response = $controller->putفنادق($this->request, $this->response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testDeleteفنادق()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM فنادق WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $controller = new فنادقController($this->pdo);
        $response = $controller->deleteفنادق($this->request, $this->response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }
}