<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use PDO;

class TestCustomers extends TestCase
{
    private $mockPdo;
    private $customersController;

    protected function setUp(): void
    {
        $this->mockPdo = $this->createMock(PDO::class);
        $this->customersController = new CustomersController($this->mockPdo);
    }

    public function testGetCustomers()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $this->mockPdo->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM customers')
            ->willReturn($this->createMock(PDOStatement::class));

        $result = $this->customersController->getCustomers($request, $response);

        $this->assertInstanceOf(ResponseInterface::class, $result);
    }

    public function testGetCustomerById()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $customerId = 1;

        $this->mockPdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM customers WHERE id = :id')
            ->willReturn($this->createMock(PDOStatement::class));

        $result = $this->customersController->getCustomerById($request, $response, $customerId);

        $this->assertInstanceOf(ResponseInterface::class, $result);
    }

    public function testCreateCustomer()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $customerData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ];

        $request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn($customerData);

        $this->mockPdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO customers (name, email) VALUES (:name, :email)')
            ->willReturn($this->createMock(PDOStatement::class));

        $result = $this->customersController->createCustomer($request, $response);

        $this->assertInstanceOf(ResponseInterface::class, $result);
    }

    public function testUpdateCustomer()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $customerId = 1;
        $customerData = [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
        ];

        $request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn($customerData);

        $this->mockPdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE customers SET name = :name, email = :email WHERE id = :id')
            ->willReturn($this->createMock(PDOStatement::class));

        $result = $this->customersController->updateCustomer($request, $response, $customerId);

        $this->assertInstanceOf(ResponseInterface::class, $result);
    }

    public function testDeleteCustomer()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $customerId = 1;

        $this->mockPdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM customers WHERE id = :id')
            ->willReturn($this->createMock(PDOStatement::class));

        $result = $this->customersController->deleteCustomer($request, $response, $customerId);

        $this->assertInstanceOf(ResponseInterface::class, $result);
    }
}