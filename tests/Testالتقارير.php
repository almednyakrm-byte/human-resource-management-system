<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Controller\ReportsController;
use App\Repository\ReportsRepository;
use App\Entity\Reports;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Paginator\PaginationInterface;

class Testالتقارير extends TestCase
{
    private $controller;
    private $repository;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock('PDO');
        $this->repository = $this->createMock(ReportsRepository::class);
        $this->controller = new ReportsController($this->repository);
    }

    public function testGetReports(): void
    {
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([
                new Reports(),
                new Reports(),
            ]);

        $response = $this->controller->getReports();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetReport(): void
    {
        $report = new Reports();
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($report);

        $response = $this->controller->getReport(1);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetReportNotFound(): void
    {
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->expectException(NotFoundHttpException::class);

        $this->controller->getReport(1);
    }

    public function testCreateReport(): void
    {
        $report = new Reports();
        $this->repository->expects($this->once())
            ->method('save')
            ->with($report)
            ->willReturn($report);

        $request = $this->createMock(Request::class);
        $request->expects($this->once())
            ->method('request')
            ->with('json')
            ->willReturn(['name' => 'Report Name']);

        $response = $this->controller->createReport($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUpdateReport(): void
    {
        $report = new Reports();
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($report);

        $request = $this->createMock(Request::class);
        $request->expects($this->once())
            ->method('request')
            ->with('json')
            ->willReturn(['name' => 'Report Name']);

        $response = $this->controller->updateReport(1, $request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testUpdateReportNotFound(): void
    {
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->expectException(NotFoundHttpException::class);

        $this->controller->updateReport(1, $this->createMock(Request::class));
    }

    public function testDeleteReport(): void
    {
        $report = new Reports();
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($report);

        $response = $this->controller->deleteReport(1);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteReportNotFound(): void
    {
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->expectException(NotFoundHttpException::class);

        $this->controller->deleteReport(1);
    }
}