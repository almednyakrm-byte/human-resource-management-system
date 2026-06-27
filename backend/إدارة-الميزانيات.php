<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized access'));
    exit;
}

// Get input data from JSON body
$input = json_decode(file_get_contents('php://input'), true);

// Define routes for CRUD operations
$routes = array(
    '/budgets' => array('GET', 'POST'),
    '/budgets/:id' => array('GET', 'PUT', 'DELETE')
);

// Get current route
$route = explode('/', $_SERVER['REQUEST_URI']);
array_shift($route); // Remove empty string
array_shift($route); // Remove 'إدارة-الميزانيات.php'

// Check if route is valid
if (!isset($routes['/' . implode('/', $route)])) {
    http_response_code(404);
    echo json_encode(array('error' => 'Route not found'));
    exit;
}

// Get allowed methods for current route
$allowedMethods = $routes['/' . implode('/', $route)];

// Check if method is allowed
if (!in_array($_SERVER['REQUEST_METHOD'], $allowedMethods)) {
    http_response_code(405);
    echo json_encode(array('error' => 'Method not allowed'));
    exit;
}

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden access'));
        exit;
    }

    // Get budget data
    $stmt = $pdo->prepare('SELECT * FROM budgets');
    $stmt->execute();
    $budgets = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return budget data
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($budgets);
    exit;
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate input data
    if (!isset($input['name']) || !isset($input['amount'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input data'));
        exit;
    }

    // Sanitize input data
    $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
    $amount = filter_var($input['amount'], FILTER_SANITIZE_NUMBER_INT);

    // Insert budget data
    $stmt = $pdo->prepare('INSERT INTO budgets (name, amount) VALUES (:name, :amount)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':amount', $amount);
    $stmt->execute();

    // Return budget data
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Budget created successfully'));
    exit;
}

// Handle PUT request
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden access'));
        exit;
    }

    // Get budget ID from route
    $budgetId = explode('/', $_SERVER['REQUEST_URI']);
    array_shift($budgetId); // Remove empty string
    array_shift($budgetId); // Remove 'إدارة-الميزانيات.php'

    // Validate input data
    if (!isset($input['name']) || !isset($input['amount'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input data'));
        exit;
    }

    // Sanitize input data
    $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
    $amount = filter_var($input['amount'], FILTER_SANITIZE_NUMBER_INT);

    // Update budget data
    $stmt = $pdo->prepare('UPDATE budgets SET name = :name, amount = :amount WHERE id = :id');
    $stmt->bindParam(':id', $budgetId[0]);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':amount', $amount);
    $stmt->execute();

    // Return budget data
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Budget updated successfully'));
    exit;
}

// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden access'));
        exit;
    }

    // Get budget ID from route
    $budgetId = explode('/', $_SERVER['REQUEST_URI']);
    array_shift($budgetId); // Remove empty string
    array_shift($budgetId); // Remove 'إدارة-الميزانيات.php'

    // Delete budget data
    $stmt = $pdo->prepare('DELETE FROM budgets WHERE id = :id');
    $stmt->bindParam(':id', $budgetId[0]);
    $stmt->execute();

    // Return budget data
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Budget deleted successfully'));
    exit;
}