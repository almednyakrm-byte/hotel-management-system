<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use PDOStatement;

class Testنزلاء extends TestCase
{
    private $pdo;
    private $statement;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->statement = $this->createMock(PDOStatement::class);
    }

    public function testGetنزلاء()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM نزلاء')
            ->willReturn($this->statement);

        $this->statement->expects($this->once())
            ->method('execute')
            ->with([]);

        $this->statement->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'نزلاء 1'],
                ['id' => 2, 'name' => 'نزلاء 2'],
            ]);

        $result = $this->getنزلاء($this->pdo);
        $this->assertEquals([
            ['id' => 1, 'name' => 'نزلاء 1'],
            ['id' => 2, 'name' => 'نزلاء 2'],
        ], $result);
    }

    public function testPostنزلاء()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO نزلاء (name) VALUES (:name)')
            ->willReturn($this->statement);

        $this->statement->expects($this->once())
            ->method('bindParam')
            ->with(':name', 'نزلاء 3');

        $this->statement->expects($this->once())
            ->method('execute')
            ->with([]);

        $this->statement->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $result = $this->postنزلاء($this->pdo, 'نزلاء 3');
        $this->assertEquals(1, $result);
    }

    public function testPutنزلاء()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE نزلاء SET name = :name WHERE id = :id')
            ->willReturn($this->statement);

        $this->statement->expects($this->once())
            ->method('bindParam')
            ->with(':name', 'نزلاء 1 updated');

        $this->statement->expects($this->once())
            ->method('bindParam')
            ->with(':id', 1);

        $this->statement->expects($this->once())
            ->method('execute')
            ->with([]);

        $this->statement->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $result = $this->putنزلاء($this->pdo, 1, 'نزلاء 1 updated');
        $this->assertEquals(1, $result);
    }

    public function testDeleteنزلاء()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM نزلاء WHERE id = :id')
            ->willReturn($this->statement);

        $this->statement->expects($this->once())
            ->method('bindParam')
            ->with(':id', 1);

        $this->statement->expects($this->once())
            ->method('execute')
            ->with([]);

        $this->statement->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $result = $this->deleteنزلاء($this->pdo, 1);
        $this->assertEquals(1, $result);
    }

    private function getنزلاء(PDO $pdo)
    {
        $statement = $pdo->prepare('SELECT * FROM نزلاء');
        $statement->execute();
        return $statement->fetchAll();
    }

    private function postنزلاء(PDO $pdo, string $name)
    {
        $statement = $pdo->prepare('INSERT INTO نزلاء (name) VALUES (:name)');
        $statement->bindParam(':name', $name);
        $statement->execute();
        return $statement->rowCount();
    }

    private function putنزلاء(PDO $pdo, int $id, string $name)
    {
        $statement = $pdo->prepare('UPDATE نزلاء SET name = :name WHERE id = :id');
        $statement->bindParam(':name', $name);
        $statement->bindParam(':id', $id);
        $statement->execute();
        return $statement->rowCount();
    }

    private function deleteنزلاء(PDO $pdo, int $id)
    {
        $statement = $pdo->prepare('DELETE FROM نزلاء WHERE id = :id');
        $statement->bindParam(':id', $id);
        $statement->execute();
        return $statement->rowCount();
    }
}