<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Define routes
$routes = [
    '/departments' => [
        'GET' => function () {
            $stmt = $pdo->prepare('SELECT * FROM departments');
            $stmt->execute();
            $departments = $stmt->fetchAll(PDO::FETCH_ASSOC);
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($departments);
        },
        'POST' => function () {
            // Validate input
            if (!isset($input['name']) || !isset($input['description'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid input']);
                exit;
            }

            // Sanitize input
            $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
            $description = filter_var($input['description'], FILTER_SANITIZE_STRING);

            // Check if user is admin
            if ($_SESSION['user_role'] !== 'admin') {
                http_response_code(403);
                echo json_encode(['error' => 'Forbidden']);
                exit;
            }

            // Insert data
            $stmt = $pdo->prepare('INSERT INTO departments (name, description) VALUES (:name, :description)');
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':description', $description);
            $stmt->execute();

            http_response_code(201);
            header('Content-Type: application/json');
            echo json_encode(['message' => 'Department created successfully']);
        },
    ],
    '/departments/{id}' => [
        'GET' => function ($id) {
            // Validate input
            if (!ctype_digit($id)) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid input']);
                exit;
            }

            // Check if user is admin
            if ($_SESSION['user_role'] !== 'admin') {
                http_response_code(403);
                echo json_encode(['error' => 'Forbidden']);
                exit;
            }

            // Select data
            $stmt = $pdo->prepare('SELECT * FROM departments WHERE id = :id');
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $department = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$department) {
                http_response_code(404);
                echo json_encode(['error' => 'Department not found']);
                exit;
            }

            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($department);
        },
        'PUT' => function ($id) {
            // Validate input
            if (!isset($input['name']) || !isset($input['description'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid input']);
                exit;
            }

            // Sanitize input
            $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
            $description = filter_var($input['description'], FILTER_SANITIZE_STRING);

            // Check if user is admin
            if ($_SESSION['user_role'] !== 'admin') {
                http_response_code(403);
                echo json_encode(['error' => 'Forbidden']);
                exit;
            }

            // Update data
            $stmt = $pdo->prepare('UPDATE departments SET name = :name, description = :description WHERE id = :id');
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(['message' => 'Department updated successfully']);
        },
        'DELETE' => function ($id) {
            // Validate input
            if (!ctype_digit($id)) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid input']);
                exit;
            }

            // Check if user is admin
            if ($_SESSION['user_role'] !== 'admin') {
                http_response_code(403);
                echo json_encode(['error' => 'Forbidden']);
                exit;
            }

            // Delete data
            $stmt = $pdo->prepare('DELETE FROM departments WHERE id = :id');
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(['message' => 'Department deleted successfully']);
        },
    ],
];

// Get route
$route = explode('/', $_SERVER['REQUEST_URI']);
$route = end($route);

// Get HTTP method
$method = $_SERVER['REQUEST_METHOD'];

// Check if route exists
if (!isset($routes[$route])) {
    http_response_code(404);
    echo json_encode(['error' => 'Route not found']);
    exit;
}

// Check if method exists
if (!isset($routes[$route][$method])) {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Call route function
$routes[$route][$method]();