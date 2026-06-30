<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Controller\ServicesController;
use App\Repository\ServicesRepository;
use App\Entity\Services;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Testخدمات extends TestCase
{
    private $servicesController;
    private $servicesRepository;

    protected function setUp(): void
    {
        $this->servicesRepository = $this->createMock(ServicesRepository::class);
        $this->servicesController = new ServicesController($this->servicesRepository);
    }

    public function testGetServices()
    {
        $expectedServices = [
            new Services('Service 1'),
            new Services('Service 2'),
        ];

        $this->servicesRepository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn($expectedServices);

        $response = $this->servicesController->getServices();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($expectedServices), $response->getContent());
    }

    public function testGetService()
    {
        $expectedService = new Services('Service 1');

        $this->servicesRepository
            ->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($expectedService);

        $response = $this->servicesController->getService(1);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($expectedService), $response->getContent());
    }

    public function testGetServiceNotFound()
    {
        $this->expectException(NotFoundHttpException::class);

        $this->servicesRepository
            ->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->servicesController->getService(1);
    }

    public function testCreateService()
    {
        $expectedService = new Services('Service 1');
        $expectedService->setId(1);

        $this->servicesRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->equalTo($expectedService))
            ->willReturn($expectedService);

        $request = new Request();
        $request->request->set('name', 'Service 1');

        $response = $this->servicesController->createService($request);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals(json_encode($expectedService), $response->getContent());
    }

    public function testUpdateService()
    {
        $expectedService = new Services('Service 1');
        $expectedService->setId(1);

        $this->servicesRepository
            ->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($expectedService);

        $this->servicesRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->equalTo($expectedService))
            ->willReturn($expectedService);

        $request = new Request();
        $request->request->set('name', 'Service 1');

        $response = $this->servicesController->updateService(1, $request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($expectedService), $response->getContent());
    }

    public function testUpdateServiceNotFound()
    {
        $this->expectException(NotFoundHttpException::class);

        $this->servicesRepository
            ->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $request = new Request();
        $request->request->set('name', 'Service 1');

        $this->servicesController->updateService(1, $request);
    }

    public function testDeleteService()
    {
        $expectedService = new Services('Service 1');
        $expectedService->setId(1);

        $this->servicesRepository
            ->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($expectedService);

        $this->servicesRepository
            ->expects($this->once())
            ->method('remove')
            ->with($this->equalTo($expectedService));

        $response = $this->servicesController->deleteService(1);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteServiceNotFound()
    {
        $this->expectException(NotFoundHttpException::class);

        $this->servicesRepository
            ->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->servicesController->deleteService(1);
    }
}