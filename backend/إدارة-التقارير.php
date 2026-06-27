<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Define routes
$routes = [
    '/reports' => [
        'GET' => function() {
            // Select all reports
            $stmt = $pdo->prepare('SELECT * FROM reports');
            $stmt->execute();
            $reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($reports);
        },
        'POST' => function() {
            // Validate input data
            if (!isset($input['title']) || !isset($input['description'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid input']);
                exit;
            }

            // Sanitize input data
            $title = filter_var($input['title'], FILTER_SANITIZE_STRING);
            $description = filter_var($input['description'], FILTER_SANITIZE_STRING);

            // Insert new report
            $stmt = $pdo->prepare('INSERT INTO reports (title, description, user_id) VALUES (:title, :description, :user_id)');
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':user_id', $_SESSION['user_id']);
            $stmt->execute();

            // Return new report ID
            http_response_code(201);
            header('Content-Type: application/json');
            echo json_encode(['id' => $pdo->lastInsertId()]);
        }
    ],
    '/reports/:id' => [
        'GET' => function($id) {
            // Check if user is admin
            if ($_SESSION['role'] !== 'admin') {
                http_response_code(403);
                echo json_encode(['error' => 'Forbidden']);
                exit;
            }

            // Select report by ID
            $stmt = $pdo->prepare('SELECT * FROM reports WHERE id = :id');
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $report = $stmt->fetch(PDO::FETCH_ASSOC);

            // Check if report exists
            if (!$report) {
                http_response_code(404);
                echo json_encode(['error' => 'Not found']);
                exit;
            }

            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($report);
        },
        'PUT' => function($id) {
            // Check if user is admin
            if ($_SESSION['role'] !== 'admin') {
                http_response_code(403);
                echo json_encode(['error' => 'Forbidden']);
                exit;
            }

            // Validate input data
            if (!isset($input['title']) || !isset($input['description'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid input']);
                exit;
            }

            // Sanitize input data
            $title = filter_var($input['title'], FILTER_SANITIZE_STRING);
            $description = filter_var($input['description'], FILTER_SANITIZE_STRING);

            // Update report
            $stmt = $pdo->prepare('UPDATE reports SET title = :title, description = :description WHERE id = :id');
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(['message' => 'Report updated successfully']);
        },
        'DELETE' => function($id) {
            // Check if user is admin
            if ($_SESSION['role'] !== 'admin') {
                http_response_code(403);
                echo json_encode(['error' => 'Forbidden']);
                exit;
            }

            // Delete report
            $stmt = $pdo->prepare('DELETE FROM reports WHERE id = :id');
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(['message' => 'Report deleted successfully']);
        }
    ]
];

// Get route and method from URL
$route = explode('/', $_SERVER['REQUEST_URI']);
$method = $_SERVER['REQUEST_METHOD'];

// Get report ID from URL
$id = isset($route[3]) ? $route[3] : null;

// Check if route and method exist
if (isset($routes['/' . $route[1]]) && isset($routes['/' . $route[1]][$method])) {
    // Call route function
    $routes['/' . $route[1]][$method]($id);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Not found']);
}