<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Rooms;

class TestRooms extends TestCase
{
    private $rooms;
    private $request;
    private $response;

    protected function setUp(): void
    {
        $this->rooms = new Rooms();
        $this->request = $this->createMock(ServerRequestInterface::class);
        $this->response = $this->createMock(ResponseInterface::class);
    }

    public function testGetRooms()
    {
        $pdo = $this->createMock(\PDO::class);
        $stmt = $this->createMock(\PDOStatement::class);

        $pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM rooms')
            ->willReturn($stmt);

        $stmt->expects($this->once())
            ->method('execute')
            ->with([]);

        $stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'Room 1'],
                ['id' => 2, 'name' => 'Room 2'],
            ]);

        $this->rooms->setPdo($pdo);
        $result = $this->rooms->getRooms($this->request, $this->response);

        $this->assertIsArray($result);
        $this->assertCount(2, $result);
    }

    public function testGetRoomById()
    {
        $pdo = $this->createMock(\PDO::class);
        $stmt = $this->createMock(\PDOStatement::class);

        $this->request->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(1);

        $pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM rooms WHERE id = :id')
            ->willReturn($stmt);

        $stmt->expects($this->once())
            ->method('execute')
            ->with([':id' => 1]);

        $stmt->expects($this->once())
            ->method('fetch')
            ->willReturn(['id' => 1, 'name' => 'Room 1']);

        $this->rooms->setPdo($pdo);
        $result = $this->rooms->getRoomById($this->request, $this->response);

        $this->assertIsArray($result);
        $this->assertEquals(1, $result['id']);
    }

    public function testCreateRoom()
    {
        $pdo = $this->createMock(\PDO::class);
        $stmt = $this->createMock(\PDOStatement::class);

        $this->request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['name' => 'New Room']);

        $pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO rooms (name) VALUES (:name)')
            ->willReturn($stmt);

        $stmt->expects($this->once())
            ->method('execute')
            ->with([':name' => 'New Room']);

        $this->rooms->setPdo($pdo);
        $result = $this->rooms->createRoom($this->request, $this->response);

        $this->assertIsArray($result);
        $this->assertEquals('New Room', $result['name']);
    }

    public function testUpdateRoom()
    {
        $pdo = $this->createMock(\PDO::class);
        $stmt = $this->createMock(\PDOStatement::class);

        $this->request->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(1);

        $this->request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['name' => 'Updated Room']);

        $pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE rooms SET name = :name WHERE id = :id')
            ->willReturn($stmt);

        $stmt->expects($this->once())
            ->method('execute')
            ->with([':name' => 'Updated Room', ':id' => 1]);

        $this->rooms->setPdo($pdo);
        $result = $this->rooms->updateRoom($this->request, $this->response);

        $this->assertIsArray($result);
        $this->assertEquals('Updated Room', $result['name']);
    }

    public function testDeleteRoom()
    {
        $pdo = $this->createMock(\PDO::class);
        $stmt = $this->createMock(\PDOStatement::class);

        $this->request->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(1);

        $pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM rooms WHERE id = :id')
            ->willReturn($stmt);

        $stmt->expects($this->once())
            ->method('execute')
            ->with([':id' => 1]);

        $this->rooms->setPdo($pdo);
        $result = $this->rooms->deleteRoom($this->request, $this->response);

        $this->assertIsArray($result);
        $this->assertEquals(1, $result['id']);
    }
}