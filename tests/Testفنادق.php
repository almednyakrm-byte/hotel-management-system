<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\HotelController;
use App\Repository\HotelRepository;
use App\Service\HotelService;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;

class Testفنادق extends TestCase
{
    private $controller;
    private $repository;
    private $service;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->repository = $this->createMock(HotelRepository::class);
        $this->service = $this->createMock(HotelService::class);
        $this->controller = new HotelController($this->repository, $this->service);
    }

    public function testGetHotels()
    {
        $hotels = [
            ['id' => 1, 'name' => 'Hotel 1'],
            ['id' => 2, 'name' => 'Hotel 2'],
        ];

        $this->pdo->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM hotels')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn($hotels);

        $response = $this->controller->getHotels();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(json_encode($hotels), $response->getBody()->getContents());
    }

    public function testCreateHotel()
    {
        $hotel = ['id' => 1, 'name' => 'Hotel 1'];

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO hotels (name) VALUES (:name)')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->repository->expects($this->once())
            ->method('create')
            ->with($hotel)
            ->willReturn($hotel);

        $response = $this->controller->createHotel($hotel);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals(json_encode($hotel), $response->getBody()->getContents());
    }

    public function testUpdateHotel()
    {
        $hotel = ['id' => 1, 'name' => 'Hotel 1'];

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE hotels SET name = :name WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->repository->expects($this->once())
            ->method('update')
            ->with($hotel)
            ->willReturn($hotel);

        $response = $this->controller->updateHotel($hotel['id'], $hotel);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(json_encode($hotel), $response->getBody()->getContents());
    }

    public function testDeleteHotel()
    {
        $hotel = ['id' => 1, 'name' => 'Hotel 1'];

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM hotels WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->repository->expects($this->once())
            ->method('delete')
            ->with($hotel['id'])
            ->willReturn(true);

        $response = $this->controller->deleteHotel($hotel['id']);

        $this->assertEquals(204, $response->getStatusCode());
    }
}


This test file covers the following scenarios:

- `testGetHotels`: Tests the GET request to retrieve all hotels.
- `testCreateHotel`: Tests the POST request to create a new hotel.
- `testUpdateHotel`: Tests the PUT request to update an existing hotel.
- `testDeleteHotel`: Tests the DELETE request to delete a hotel.

Each test method uses mocking to simulate the behavior of the PDO statements and the HotelRepository and HotelService classes. The expected behavior is then verified using assertions.