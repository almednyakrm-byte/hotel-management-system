<?php

declare(strict_types=1);

namespace App\Tests;

use App\Services\ServiceController;
use App\Services\ServiceModel;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use PDO;
use PDOStatement;

class TestServices extends TestCase
{
    private ServiceController $serviceController;
    private ServiceModel $serviceModel;
    private MockObject $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->serviceModel = new ServiceModel($this->pdo);
        $this->serviceController = new ServiceController($this->serviceModel);
    }

    public function testGetServices(): void
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'Service 1'],
                ['id' => 2, 'name' => 'Service 2'],
            ]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM services')
            ->willReturn($stmt);

        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $result = $this->serviceController->getServices($request, $response);

        $this->assertEquals(200, $result->getStatusCode());
        $this->assertEquals('application/json', $result->getHeaderLine('Content-Type'));
    }

    public function testGetServiceById(): void
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('fetch')
            ->willReturn(['id' => 1, 'name' => 'Service 1']);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM services WHERE id = :id')
            ->willReturn($stmt);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(1);

        $response = $this->createMock(ResponseInterface::class);

        $result = $this->serviceController->getServiceById($request, $response);

        $this->assertEquals(200, $result->getStatusCode());
        $this->assertEquals('application/json', $result->getHeaderLine('Content-Type'));
    }

    public function testCreateService(): void
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with(['name' => 'New Service']);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO services (name) VALUES (:name)')
            ->willReturn($stmt);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['name' => 'New Service']);

        $response = $this->createMock(ResponseInterface::class);

        $result = $this->serviceController->createService($request, $response);

        $this->assertEquals(201, $result->getStatusCode());
        $this->assertEquals('application/json', $result->getHeaderLine('Content-Type'));
    }

    public function testUpdateService(): void
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with(['id' => 1, 'name' => 'Updated Service']);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE services SET name = :name WHERE id = :id')
            ->willReturn($stmt);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(1);
        $request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['name' => 'Updated Service']);

        $response = $this->createMock(ResponseInterface::class);

        $result = $this->serviceController->updateService($request, $response);

        $this->assertEquals(200, $result->getStatusCode());
        $this->assertEquals('application/json', $result->getHeaderLine('Content-Type'));
    }

    public function testDeleteService(): void
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with(['id' => 1]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM services WHERE id = :id')
            ->willReturn($stmt);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(1);

        $response = $this->createMock(ResponseInterface::class);

        $result = $this->serviceController->deleteService($request, $response);

        $this->assertEquals(204, $result->getStatusCode());
    }
}