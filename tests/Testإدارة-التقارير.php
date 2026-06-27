<?php

namespace App\Tests\Controller;

use App\Controller\ReportController;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Testإدارةالتقارير extends TestCase
{
    private $controller;
    private $pdoMock;

    protected function setUp(): void
    {
        $this->pdoMock = $this->createMock(PDO::class);
        $this->controller = new ReportController($this->pdoMock);
    }

    public function testGetReports()
    {
        $this->pdoMock->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM reports')
            ->willReturn($this->createMock(\PDOStatement::class));

        $response = $this->controller->getReports();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testCreateReport()
    {
        $reportData = [
            'title' => 'Test Report',
            'description' => 'This is a test report',
        ];

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO reports (title, description) VALUES (:title, :description)')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new Request([], [], [], [], [], json_encode($reportData));
        $response = $this->controller->createReport($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testUpdateReport()
    {
        $reportId = 1;
        $reportData = [
            'title' => 'Updated Report',
            'description' => 'This is an updated report',
        ];

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('UPDATE reports SET title = :title, description = :description WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new Request([], [], [], [], [], json_encode($reportData));
        $response = $this->controller->updateReport($reportId, $request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testDeleteReport()
    {
        $reportId = 1;

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM reports WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $response = $this->controller->deleteReport($reportId);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }
}


This test file covers the following scenarios:

1. `testGetReports`: Verifies that the `getReports` method returns a successful response (200) when querying the database for reports.
2. `testCreateReport`: Verifies that the `createReport` method returns a successful response (201) when creating a new report.
3. `testUpdateReport`: Verifies that the `updateReport` method returns a successful response (200) when updating an existing report.
4. `testDeleteReport`: Verifies that the `deleteReport` method returns a successful response (200) when deleting a report.

Note that this test file assumes that the `ReportController` class has the following methods:

* `getReports`: Retrieves a list of reports from the database.
* `createReport`: Creates a new report in the database.
* `updateReport`: Updates an existing report in the database.
* `deleteReport`: Deletes a report from the database.

Also, this test file uses the `createMock` method to create mock objects for the `PDO` and `PDOStatement` classes. This allows us to isolate the dependencies of the `ReportController` class and test its behavior in isolation.