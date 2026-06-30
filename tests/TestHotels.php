<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Hotels;

class TestHotels extends TestCase
{
    private $hotel;
    private $request;
    private $response;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(\PDO::class);
        $this->hotel = new Hotels($this->pdo);
        $this->request = $this->createMock(ServerRequestInterface::class);
        $this->response = $this->createMock(ResponseInterface::class);
    }

    public function testGetAllHotels()
    {
        $this->pdo->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM hotels')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->hotel->getAllHotels($this->request, $this->response);
    }

    public function testGetHotelById()
    {
        $id = 1;
        $this->request->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn($id);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM hotels WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->hotel->getHotelById($this->request, $this->response);
    }

    public function testCreateHotel()
    {
        $data = [
            'name' => 'Hotel Test',
            'address' => 'Test Address',
            'city' => 'Test City',
            'state' => 'Test State',
            'country' => 'Test Country',
            'zip' => '12345',
        ];

        $this->request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn($data);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO hotels (name, address, city, state, country, zip) VALUES (:name, :address, :city, :state, :country, :zip)')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->hotel->createHotel($this->request, $this->response);
    }

    public function testUpdateHotel()
    {
        $id = 1;
        $data = [
            'name' => 'Hotel Test Updated',
            'address' => 'Test Address Updated',
            'city' => 'Test City Updated',
            'state' => 'Test State Updated',
            'country' => 'Test Country Updated',
            'zip' => '12345',
        ];

        $this->request->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn($id);

        $this->request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn($data);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE hotels SET name = :name, address = :address, city = :city, state = :state, country = :country, zip = :zip WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->hotel->updateHotel($this->request, $this->response);
    }

    public function testDeleteHotel()
    {
        $id = 1;

        $this->request->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn($id);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM hotels WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->hotel->deleteHotel($this->request, $this->response);
    }
}