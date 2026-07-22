<?php

declare(strict_types=1);

namespace App\Tests;

use App\Bookings;
use App\Database;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use PDO;
use PDOStatement;

class TestBookings extends TestCase
{
    private $bookings;
    private $database;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->database = new Database($this->pdo);
        $this->bookings = new Bookings($this->database);
    }

    public function testGetBookings(): void
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([]);

        $stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'Test Booking'],
                ['id' => 2, 'name' => 'Another Test Booking'],
            ]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM bookings')
            ->willReturn($stmt);

        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $result = $this->bookings->getBookings($request, $response);

        $this->assertIsArray($result);
        $this->assertCount(2, $result);
    }

    public function testGetBookingById(): void
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([1]);

        $stmt->expects($this->once())
            ->method('fetch')
            ->willReturn(['id' => 1, 'name' => 'Test Booking']);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM bookings WHERE id = ?')
            ->willReturn($stmt);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(1);

        $response = $this->createMock(ResponseInterface::class);

        $result = $this->bookings->getBookingById($request, $response);

        $this->assertIsArray($result);
        $this->assertEquals(1, $result['id']);
    }

    public function testCreateBooking(): void
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with(['Test Booking']);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO bookings (name) VALUES (?)')
            ->willReturn($stmt);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['name' => 'Test Booking']);

        $response = $this->createMock(ResponseInterface::class);

        $result = $this->bookings->createBooking($request, $response);

        $this->assertIsArray($result);
        $this->assertEquals(201, $result['status']);
    }

    public function testUpdateBooking(): void
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with(['Test Booking', 1]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE bookings SET name = ? WHERE id = ?')
            ->willReturn($stmt);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(1);

        $request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['name' => 'Test Booking']);

        $response = $this->createMock(ResponseInterface::class);

        $result = $this->bookings->updateBooking($request, $response);

        $this->assertIsArray($result);
        $this->assertEquals(200, $result['status']);
    }

    public function testDeleteBooking(): void
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([1]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM bookings WHERE id = ?')
            ->willReturn($stmt);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(1);

        $response = $this->createMock(ResponseInterface::class);

        $result = $this->bookings->deleteBooking($request, $response);

        $this->assertIsArray($result);
        $this->assertEquals(204, $result['status']);
    }
}