<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\الميزانياتController;
use App\Repository\الميزانياتRepository;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Testالميزانيات extends TestCase
{
    private $controller;
    private $repository;
    private $mockPDO;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(الميزانياتRepository::class);
        $this->mockPDO = $this->createMock(\PDO::class);

        $this->controller = new الميزانياتController($this->repository);
    }

    public function testGetAll(): void
    {
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([
                ['id' => 1, 'name' => 'Test Budget'],
                ['id' => 2, 'name' => 'Another Budget'],
            ]);

        $response = $this->controller->getAll();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testGetById(): void
    {
        $id = 1;
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(['id' => $id, 'name' => 'Test Budget']);

        $response = $this->controller->getById($id);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testGetByIdNotFound(): void
    {
        $id = 1;
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(null);

        $this->expectException(NotFoundHttpException::class);

        $this->controller->getById($id);
    }

    public function testCreate(): void
    {
        $data = ['name' => 'Test Budget'];
        $this->repository->expects($this->once())
            ->method('create')
            ->with($data)
            ->willReturn(['id' => 1, 'name' => 'Test Budget']);

        $response = $this->controller->create($data);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testUpdate(): void
    {
        $id = 1;
        $data = ['name' => 'Updated Budget'];
        $this->repository->expects($this->once())
            ->method('update')
            ->with($id, $data)
            ->willReturn(['id' => $id, 'name' => 'Updated Budget']);

        $response = $this->controller->update($id, $data);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testUpdateNotFound(): void
    {
        $id = 1;
        $data = ['name' => 'Updated Budget'];
        $this->repository->expects($this->once())
            ->method('update')
            ->with($id, $data)
            ->willReturn(null);

        $this->expectException(NotFoundHttpException::class);

        $this->controller->update($id, $data);
    }

    public function testDelete(): void
    {
        $id = 1;
        $this->repository->expects($this->once())
            ->method('delete')
            ->with($id);

        $response = $this->controller->delete($id);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}


This test file covers the following scenarios:

1.  `testGetAll()`: Tests the `getAll()` method to ensure it returns a successful response with a list of budgets.
2.  `testGetById()`: Tests the `getById()` method to ensure it returns a successful response with a specific budget.
3.  `testGetByIdNotFound()`: Tests the `getById()` method to ensure it throws a `NotFoundHttpException` when the budget is not found.
4.  `testCreate()`: Tests the `create()` method to ensure it returns a successful response with a newly created budget.
5.  `testUpdate()`: Tests the `update()` method to ensure it returns a successful response with an updated budget.
6.  `testUpdateNotFound()`: Tests the `update()` method to ensure it throws a `NotFoundHttpException` when the budget is not found.
7.  `testDelete()`: Tests the `delete()` method to ensure it returns a successful response after deleting a budget.

These tests use the `createMock()` method to create mock objects for the `الميزانياتRepository` and `PDO` classes. The `expects()` method is used to specify the expected behavior of the mock objects. The `willReturn()` method is used to specify the return value of the mock objects.