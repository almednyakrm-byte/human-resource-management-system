<?php

namespace App\Tests\Controller;

use App\Controller\BudgetController;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class Testإدارة-الميزانيات extends TestCase
{
    private $controller;
    private $router;
    private $tokenStorage;
    private $pdoMock;

    protected function setUp(): void
    {
        $this->router = $this->createMock(RouterInterface::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->pdoMock = $this->createMock(\PDO::class);

        $this->controller = new BudgetController($this->router, $this->tokenStorage, $this->pdoMock);
    }

    public function testGetBudgets()
    {
        $this->pdoMock->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM budgets')
            ->willReturn($this->createMock(\PDOStatement::class));

        $response = $this->controller->getBudgets();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testCreateBudget()
    {
        $request = new Request([], [], ['budget' => ['name' => 'Test Budget', 'amount' => 100]]);
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO budgets (name, amount) VALUES (:name, :amount)')
            ->willReturn($this->createMock(\PDOStatement::class));

        $response = $this->controller->createBudget($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUpdateBudget()
    {
        $request = new Request([], [], ['budget' => ['id' => 1, 'name' => 'Updated Budget', 'amount' => 200]]);
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('UPDATE budgets SET name = :name, amount = :amount WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $response = $this->controller->updateBudget($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDeleteBudget()
    {
        $request = new Request([], [], ['id' => 1]);
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM budgets WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $response = $this->controller->deleteBudget($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}


This test file uses PHPUnit to test the CRUD API operations on the 'إدارة الميزانيات' module. It creates a mock PDO object and uses it to simulate the database interactions. The tests cover the following scenarios:

*   `testGetBudgets`: Tests the GET request to retrieve all budgets.
*   `testCreateBudget`: Tests the POST request to create a new budget.
*   `testUpdateBudget`: Tests the PUT request to update an existing budget.
*   `testDeleteBudget`: Tests the DELETE request to delete a budget.

Each test method uses the mock PDO object to simulate the database interactions and verifies the response status code and type.