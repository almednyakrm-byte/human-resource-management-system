<?php
require_once 'db.php';

// Get user role from session
$userRole = $_SESSION['userRole'];

// Check if user is logged in
if (!isset($_SESSION['loggedIn'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->prepare('SELECT * FROM employees');
    $stmt->execute();
    $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($employees);
    exit;
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (empty($data)) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request body'));
        exit;
    }

    // Validate and sanitize input data
    $requiredFields = array('name', 'email', 'role');
    foreach ($requiredFields as $field) {
        if (!isset($data[$field]) || empty($data[$field])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Missing required field: ' . $field));
            exit;
        }
        $data[$field] = trim($data[$field]);
    }

    // Insert new employee
    $stmt = $pdo->prepare('INSERT INTO employees (name, email, role) VALUES (:name, :email, :role)');
    $stmt->execute(array(
        ':name' => $data['name'],
        ':email' => $data['email'],
        ':role' => $data['role']
    ));
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Employee created successfully'));
    exit;
}

// Handle PUT request
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (empty($data)) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request body'));
        exit;
    }

    // Validate and sanitize input data
    $requiredFields = array('id', 'name', 'email', 'role');
    foreach ($requiredFields as $field) {
        if (!isset($data[$field]) || empty($data[$field])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Missing required field: ' . $field));
            exit;
        }
        $data[$field] = trim($data[$field]);
    }

    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Update existing employee
    $stmt = $pdo->prepare('UPDATE employees SET name = :name, email = :email, role = :role WHERE id = :id');
    $stmt->execute(array(
        ':id' => $data['id'],
        ':name' => $data['name'],
        ':email' => $data['email'],
        ':role' => $data['role']
    ));
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Employee updated successfully'));
    exit;
}

// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (empty($data)) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request body'));
        exit;
    }

    // Validate and sanitize input data
    if (!isset($data['id']) || empty($data['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Missing required field: id'));
        exit;
    }
    $data['id'] = trim($data['id']);

    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Delete employee
    $stmt = $pdo->prepare('DELETE FROM employees WHERE id = :id');
    $stmt->execute(array(':id' => $data['id']));
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Employee deleted successfully'));
    exit;
}