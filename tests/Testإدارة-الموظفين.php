<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\EmpleadoController;
use App\Repository\EmpleadoRepository;
use App\Service\EmpleadoService;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TestEmpleadosController extends TestCase
{
    private $controller;
    private $repository;
    private $service;
    private $pdo;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(EmpleadoRepository::class);
        $this->service = $this->createMock(EmpleadoService::class);
        $this->pdo = $this->createMock(\PDO::class);

        $this->controller = new EmpleadoController($this->repository, $this->service, $this->pdo);
    }

    public function testGetEmpleados()
    {
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([
                ['id' => 1, 'nombre' => 'Juan'],
                ['id' => 2, 'nombre' => 'Pedro']
            ]);

        $response = $this->controller->getEmpleados();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
        $this->assertJson($response->getContent());
    }

    public function testGetEmpleado()
    {
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(['id' => 1, 'nombre' => 'Juan']);

        $response = $this->controller->getEmpleado(1);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
        $this->assertJson($response->getContent());
    }

    public function testGetEmpleadoNotFound()
    {
        $this->expectException(NotFoundHttpException::class);

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->controller->getEmpleado(1);
    }

    public function testCreateEmpleado()
    {
        $data = ['nombre' => 'Juan', 'edad' => 30];

        $this->service->expects($this->once())
            ->method('createEmpleado')
            ->with($data)
            ->willReturn(['id' => 1, 'nombre' => 'Juan']);

        $response = $this->controller->createEmpleado(Request::create('/empleados', 'POST', [], [], [], json_encode($data)));

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
        $this->assertJson($response->getContent());
    }

    public function testUpdateEmpleado()
    {
        $data = ['nombre' => 'Juan', 'edad' => 30];

        $this->service->expects($this->once())
            ->method('updateEmpleado')
            ->with(1, $data)
            ->willReturn(['id' => 1, 'nombre' => 'Juan']);

        $response = $this->controller->updateEmpleado(1, Request::create('/empleados/1', 'PUT', [], [], [], json_encode($data)));

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
        $this->assertJson($response->getContent());
    }

    public function testUpdateEmpleadoNotFound()
    {
        $this->expectException(NotFoundHttpException::class);

        $data = ['nombre' => 'Juan', 'edad' => 30];

        $this->service->expects($this->once())
            ->method('updateEmpleado')
            ->with(1, $data)
            ->willReturn(null);

        $this->controller->updateEmpleado(1, Request::create('/empleados/1', 'PUT', [], [], [], json_encode($data)));
    }

    public function testDeleteEmpleado()
    {
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(['id' => 1, 'nombre' => 'Juan']);

        $response = $this->controller->deleteEmpleado(1);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
        $this->assertJson($response->getContent());
    }

    public function testDeleteEmpleadoNotFound()
    {
        $this->expectException(NotFoundHttpException::class);

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->controller->deleteEmpleado(1);
    }
}