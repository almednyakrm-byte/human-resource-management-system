<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\EmployeeController;
use App\Repository\EmployeeRepository;
use App\Service\EmployeeService;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

class Testالموظفين extends TestCase
{
    private $employeeController;
    private $employeeRepository;
    private $employeeService;
    private $router;

    protected function setUp(): void
    {
        $this->employeeRepository = $this->createMock(EmployeeRepository::class);
        $this->employeeService = $this->createMock(EmployeeService::class);
        $this->router = $this->createMock(RouterInterface::class);

        $this->employeeController = new EmployeeController(
            $this->employeeRepository,
            $this->employeeService,
            $this->router
        );
    }

    public function testGetAllEmployees()
    {
        $this->employeeRepository->expects($this->once())
            ->method('findAll')
            ->willReturn([
                ['id' => 1, 'name' => 'John Doe'],
                ['id' => 2, 'name' => 'Jane Doe'],
            ]);

        $response = $this->employeeController->getAllEmployees();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testCreateEmployee()
    {
        $employeeData = ['name' => 'John Doe', 'email' => 'john@example.com'];

        $this->employeeService->expects($this->once())
            ->method('createEmployee')
            ->with($employeeData)
            ->willReturn(['id' => 1, 'name' => 'John Doe']);

        $request = new Request([], [], [], [], [], json_encode($employeeData));
        $response = $this->employeeController->createEmployee($request);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testUpdateEmployee()
    {
        $employeeId = 1;
        $employeeData = ['name' => 'John Doe Updated', 'email' => 'john@example.com'];

        $this->employeeService->expects($this->once())
            ->method('updateEmployee')
            ->with($employeeId, $employeeData)
            ->willReturn(['id' => 1, 'name' => 'John Doe Updated']);

        $request = new Request([], [], [], [], [], json_encode($employeeData));
        $response = $this->employeeController->updateEmployee($employeeId, $request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testDeleteEmployee()
    {
        $employeeId = 1;

        $this->employeeRepository->expects($this->once())
            ->method('delete')
            ->with($employeeId);

        $response = $this->employeeController->deleteEmployee($employeeId);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}


This test file covers the following scenarios:

1. `testGetAllEmployees()`: Tests the `getAllEmployees` method by mocking the `findAll` method of the `EmployeeRepository` to return a list of employees.
2. `testCreateEmployee()`: Tests the `createEmployee` method by mocking the `createEmployee` method of the `EmployeeService` to return a created employee.
3. `testUpdateEmployee()`: Tests the `updateEmployee` method by mocking the `updateEmployee` method of the `EmployeeService` to return an updated employee.
4. `testDeleteEmployee()`: Tests the `deleteEmployee` method by mocking the `delete` method of the `EmployeeRepository` to delete an employee.

Note that this is a basic example and you may need to modify it to fit your specific use case. Additionally, you will need to implement the `EmployeeRepository` and `EmployeeService` classes to make this test work.