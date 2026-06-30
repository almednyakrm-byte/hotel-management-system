<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Controllers\BookingsController;
use App\Models\BookingsModel;
use PDO;

class TestBookings extends TestCase
{
    private $bookingsController;
    private $bookingsModel;
    private $request;
    private $response;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->bookingsModel = new BookingsModel($this->pdo);
        $this->bookingsController = new BookingsController($this->bookingsModel);
        $this->request = $this->createMock(ServerRequestInterface::class);
        $this->response = $this->createMock(ResponseInterface::class);
    }

    public function testGetBookings()
    {
        $this->pdo
            ->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM bookings')
            ->willReturn($this->createMock(PDOStatement::class));

        $this->request
            ->expects($this->once())
            ->method('getMethod')
            ->willReturn('GET');

        $result = $this->bookingsController->index($this->request, $this->response);
        $this->assertIsArray($result);
    }

    public function testGetBookingById()
    {
        $id = 1;
        $this->pdo
            ->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM bookings WHERE id = :id')
            ->willReturn($this->createMock(PDOStatement::class));

        $this->request
            ->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn($id);

        $this->request
            ->expects($this->once())
            ->method('getMethod')
            ->willReturn('GET');

        $result = $this->bookingsController->show($this->request, $this->response);
        $this->assertIsArray($result);
    }

    public function testCreateBooking()
    {
        $data = [
            'name' => 'Test Booking',
            'date' => '2024-01-01',
            'time' => '10:00:00',
        ];

        $this->pdo
            ->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO bookings (name, date, time) VALUES (:name, :date, :time)')
            ->willReturn($this->createMock(PDOStatement::class));

        $this->request
            ->expects($this->once())
            ->method('getParsedBody')
            ->willReturn($data);

        $this->request
            ->expects($this->once())
            ->method('getMethod')
            ->willReturn('POST');

        $result = $this->bookingsController->store($this->request, $this->response);
        $this->assertIsArray($result);
    }

    public function testUpdateBooking()
    {
        $id = 1;
        $data = [
            'name' => 'Updated Test Booking',
            'date' => '2024-01-02',
            'time' => '11:00:00',
        ];

        $this->pdo
            ->expects($this->once())
            ->method('prepare')
            ->with('UPDATE bookings SET name = :name, date = :date, time = :time WHERE id = :id')
            ->willReturn($this->createMock(PDOStatement::class));

        $this->request
            ->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn($id);

        $this->request
            ->expects($this->once())
            ->method('getParsedBody')
            ->willReturn($data);

        $this->request
            ->expects($this->once())
            ->method('getMethod')
            ->willReturn('PUT');

        $result = $this->bookingsController->update($this->request, $this->response);
        $this->assertIsArray($result);
    }

    public function testDeleteBooking()
    {
        $id = 1;

        $this->pdo
            ->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM bookings WHERE id = :id')
            ->willReturn($this->createMock(PDOStatement::class));

        $this->request
            ->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn($id);

        $this->request
            ->expects($this->once())
            ->method('getMethod')
            ->willReturn('DELETE');

        $result = $this->bookingsController->destroy($this->request, $this->response);
        $this->assertIsArray($result);
    }
}