<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use PDOStatement;

class Testإدارة_فنادق extends TestCase
{
    private MockObject $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
    }

    public function testGetAllHotels(): void
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([]);
        $stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'Hotel 1'],
                ['id' => 2, 'name' => 'Hotel 2'],
            ]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM إدارة_فنادق')
            ->willReturn($stmt);

        $result = $this->getHotels();
        $this->assertCount(2, $result);
    }

    public function testGetHotelById(): void
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([1]);
        $stmt->expects($this->once())
            ->method('fetch')
            ->willReturn(['id' => 1, 'name' => 'Hotel 1']);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM إدارة_فنادق WHERE id = ?')
            ->willReturn($stmt);

        $result = $this->getHotel(1);
        $this->assertEquals(1, $result['id']);
    }

    public function testCreateHotel(): void
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with(['Hotel 1']);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO إدارة_فنادق (name) VALUES (?)')
            ->willReturn($stmt);

        $this->createHotel('Hotel 1');
    }

    public function testUpdateHotel(): void
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with(['Hotel 1', 1]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE إدارة_فنادق SET name = ? WHERE id = ?')
            ->willReturn($stmt);

        $this->updateHotel(1, 'Hotel 1');
    }

    public function testDeleteHotel(): void
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with([1]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM إدارة_فنادق WHERE id = ?')
            ->willReturn($stmt);

        $this->deleteHotel(1);
    }

    private function getHotels(): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM إدارة_فنادق');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    private function getHotel(int $id): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM إدارة_فنادق WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    private function createHotel(string $name): void
    {
        $stmt = $this->pdo->prepare('INSERT INTO إدارة_فنادق (name) VALUES (?)');
        $stmt->execute([$name]);
    }

    private function updateHotel(int $id, string $name): void
    {
        $stmt = $this->pdo->prepare('UPDATE إدارة_فنادق SET name = ? WHERE id = ?');
        $stmt->execute([$name, $id]);
    }

    private function deleteHotel(int $id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM إدارة_فنادق WHERE id = ?');
        $stmt->execute([$id]);
    }
}