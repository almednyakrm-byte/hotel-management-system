<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\تقاريرController;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;

class Testتقارير extends TestCase
{
    private $controller;
    private $pdoMock;

    protected function setUp(): void
    {
        $this->pdoMock = $this->createMock(PDO::class);
        $this->controller = new تقاريرController($this->pdoMock);
    }

    public function testGetAllReports()
    {
        $this->pdoMock->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM تقارير')
            ->willReturn($this->createMock(\PDOStatement::class));

        $response = $this->controller->getAllReports();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetReportById()
    {
        $reportId = 1;
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM تقارير WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));
        $this->pdoMock->expects($this->once())
            ->method('bindParam')
            ->with(':id', $reportId);

        $response = $this->controller->getReportById($reportId);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testCreateReport()
    {
        $reportData = ['title' => 'Test Report', 'description' => 'This is a test report'];
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO تقارير (title, description) VALUES (:title, :description)')
            ->willReturn($this->createMock(\PDOStatement::class));
        $this->pdoMock->expects($this->once())
            ->method('bindParam')
            ->with(':title', $reportData['title']);
        $this->pdoMock->expects($this->once())
            ->method('bindParam')
            ->with(':description', $reportData['description']);

        $response = $this->controller->createReport($reportData);
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testUpdateReport()
    {
        $reportId = 1;
        $reportData = ['title' => 'Updated Test Report', 'description' => 'This is an updated test report'];
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('UPDATE تقارير SET title = :title, description = :description WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));
        $this->pdoMock->expects($this->once())
            ->method('bindParam')
            ->with(':title', $reportData['title']);
        $this->pdoMock->expects($this->once())
            ->method('bindParam')
            ->with(':description', $reportData['description']);
        $this->pdoMock->expects($this->once())
            ->method('bindParam')
            ->with(':id', $reportId);

        $response = $this->controller->updateReport($reportId, $reportData);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testDeleteReport()
    {
        $reportId = 1;
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM تقارير WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));
        $this->pdoMock->expects($this->once())
            ->method('bindParam')
            ->with(':id', $reportId);

        $response = $this->controller->deleteReport($reportId);
        $this->assertEquals(200, $response->getStatusCode());
    }
}