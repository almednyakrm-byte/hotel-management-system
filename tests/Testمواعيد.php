<?php

namespace App\Tests\Controller;

use App\Controller\MoawedController;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Stream;
use Zend\Diactoros\Uri;

class TestMoawed extends TestCase
{
    private $controller;
    private $request;
    private $response;
    private $pdoMock;

    protected function setUp(): void
    {
        $this->controller = new MoawedController();
        $this->request = new ServerRequest('GET', '/moawed');
        $this->response = new Response();
        $this->pdoMock = $this->createMock(\PDO::class);
    }

    public function testGetMoawed()
    {
        $this->pdoMock->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM moawed')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM moawed')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->pdoMock->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->pdoMock->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'Moawed 1'],
                ['id' => 2, 'name' => 'Moawed 2'],
            ]);

        $this->controller->setPdo($this->pdoMock);
        $this->controller->getMoawed($this->request, $this->response);
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertEquals('application/json', $this->response->getHeaderLine('Content-Type'));
    }

    public function testPostMoawed()
    {
        $data = ['name' => 'Moawed 1'];
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO moawed (name) VALUES (:name)')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->pdoMock->expects($this->once())
            ->method('execute')
            ->with($data)
            ->willReturn(true);

        $this->controller->setPdo($this->pdoMock);
        $this->request = new ServerRequest('POST', '/moawed', [], json_encode($data));
        $this->controller->postMoawed($this->request, $this->response);
        $this->assertEquals(201, $this->response->getStatusCode());
        $this->assertEquals('application/json', $this->response->getHeaderLine('Content-Type'));
    }

    public function testPutMoawed()
    {
        $data = ['name' => 'Moawed 1'];
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('UPDATE moawed SET name = :name WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->pdoMock->expects($this->once())
            ->method('execute')
            ->with($data)
            ->willReturn(true);

        $this->controller->setPdo($this->pdoMock);
        $this->request = new ServerRequest('PUT', '/moawed/1', [], json_encode($data));
        $this->controller->putMoawed($this->request, $this->response);
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertEquals('application/json', $this->response->getHeaderLine('Content-Type'));
    }

    public function testDeleteMoawed()
    {
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM moawed WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->pdoMock->expects($this->once())
            ->method('execute')
            ->with(['id' => 1])
            ->willReturn(true);

        $this->controller->setPdo($this->pdoMock);
        $this->request = new ServerRequest('DELETE', '/moawed/1');
        $this->controller->deleteMoawed($this->request, $this->response);
        $this->assertEquals(204, $this->response->getStatusCode());
        $this->assertEquals('application/json', $this->response->getHeaderLine('Content-Type'));
    }
}


This test file covers the CRUD operations for the 'مواعيد' module using mocked PDO statements. It tests the GET, POST, PUT, and DELETE requests. The `setUp` method is used to create a new instance of the `MoawedController` and the `PDO` mock object. The `testGetMoawed`, `testPostMoawed`, `testPutMoawed`, and `testDeleteMoawed` methods test each of the CRUD operations.