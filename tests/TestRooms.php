<?php

declare(strict_types=1);

namespace App\Tests;

use App\Rooms;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use PDOStatement;

class TestRooms extends TestCase
{
    private $rooms;
    private $pdo;
    private $stmt;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->stmt = $this->createMock(PDOStatement::class);
        $this->rooms = new Rooms($this->pdo);
    }

    public function testGetRooms(): void
    {
        $this->pdo
            ->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM rooms')
            ->willReturn($this->stmt);

        $this->stmt
            ->expects($this->once())
            ->method('execute')
            ->with([]);

        $this->stmt
            ->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'Room 1'],
                ['id' => 2, 'name' => 'Room 2'],
            ]);

        $result = $this->rooms->getRooms();
        $this->assertEquals([
            ['id' => 1, 'name' => 'Room 1'],
            ['id' => 2, 'name' => 'Room 2'],
        ], $result);
    }

    public function testCreateRoom(): void
    {
        $this->pdo
            ->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO rooms (name) VALUES (:name)')
            ->willReturn($this->stmt);

        $this->stmt
            ->expects($this->once())
            ->method('execute')
            ->with([':name' => 'New Room']);

        $this->stmt
            ->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $result = $this->rooms->createRoom('New Room');
        $this->assertEquals(1, $result);
    }

    public function testUpdateRoom(): void
    {
        $this->pdo
            ->expects($this->once())
            ->method('prepare')
            ->with('UPDATE rooms SET name = :name WHERE id = :id')
            ->willReturn($this->stmt);

        $this->stmt
            ->expects($this->once())
            ->method('execute')
            ->with([':id' => 1, ':name' => 'Updated Room']);

        $this->stmt
            ->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $result = $this->rooms->updateRoom(1, 'Updated Room');
        $this->assertEquals(1, $result);
    }

    public function testDeleteRoom(): void
    {
        $this->pdo
            ->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM rooms WHERE id = :id')
            ->willReturn($this->stmt);

        $this->stmt
            ->expects($this->once())
            ->method('execute')
            ->with([':id' => 1]);

        $this->stmt
            ->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $result = $this->rooms->deleteRoom(1);
        $this->assertEquals(1, $result);
    }
}