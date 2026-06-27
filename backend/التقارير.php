<?php

require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true) ?: $_POST;

// Define routes
$routes = [
    'GET' => [
        '/reports' => function () {
            // Get all reports
            $stmt = $pdo->prepare('SELECT * FROM reports');
            $stmt->execute();
            $reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($reports);
        },
        '/reports/:id' => function ($id) {
            // Get report by ID
            $stmt = $pdo->prepare('SELECT * FROM reports WHERE id = :id');
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $report = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$report) {
                http_response_code(404);
                echo json_encode(['error' => 'Report not found']);
                exit;
            }
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($report);
        }
    ],
    'POST' => [
        '/reports' => function () {
            // Create new report
            if (!isset($input['title']) || !isset($input['description'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid input']);
                exit;
            }
            $title = filter_var($input['title'], FILTER_SANITIZE_STRING);
            $description = filter_var($input['description'], FILTER_SANITIZE_STRING);
            $stmt = $pdo->prepare('INSERT INTO reports (title, description, user_id) VALUES (:title, :description, :user_id)');
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':user_id', $_SESSION['user_id']);
            $stmt->execute();
            http_response_code(201);
            header('Content-Type: application/json');
            echo json_encode(['message' => 'Report created successfully']);
        }
    ],
    'PUT' => [
        '/reports/:id' => function ($id) {
            // Update report
            if (!isset($input['title']) || !isset($input['description'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid input']);
                exit;
            }
            $title = filter_var($input['title'], FILTER_SANITIZE_STRING);
            $description = filter_var($input['description'], FILTER_SANITIZE_STRING);
            if ($_SESSION['role'] !== 'admin') {
                http_response_code(403);
                echo json_encode(['error' => 'Forbidden']);
                exit;
            }
            $stmt = $pdo->prepare('UPDATE reports SET title = :title, description = :description WHERE id = :id');
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(['message' => 'Report updated successfully']);
        }
    ],
    'DELETE' => [
        '/reports/:id' => function ($id) {
            // Delete report
            if ($_SESSION['role'] !== 'admin') {
                http_response_code(403);
                echo json_encode(['error' => 'Forbidden']);
                exit;
            }
            $stmt = $pdo->prepare('DELETE FROM reports WHERE id = :id');
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(['message' => 'Report deleted successfully']);
        }
    ]
];

// Get route and method
$method = $_SERVER['REQUEST_METHOD'];
$route = $_SERVER['REQUEST_URI'];

// Parse route parameters
$matches = [];
preg_match('/\/reports\/([0-9]+)/', $route, $matches);
$id = $matches[1];

// Get route handler
$handler = $routes[$method][$route] ?? null;

// Call route handler
if ($handler) {
    $handler($id);
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}