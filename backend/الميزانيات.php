<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get user role
$user_role = $_SESSION['user_role'];

// Get input data
$input_data = json_decode(file_get_contents('php://input'), true);

// Validate input data
if ($input_data === null) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request data']);
    exit;
}

// Define database table name
$table_name = 'الميزانيات';

// GET operation
if (isset($input_data['action']) && $input_data['action'] == 'get') {
    // Prepare SQL query
    $stmt = $pdo->prepare("SELECT * FROM $table_name");
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return data
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

// POST operation
if (isset($input_data['action']) && $input_data['action'] == 'create') {
    // Validate input data
    if (!isset($input_data['name']) || !isset($input_data['description'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request data']);
        exit;
    }

    // Sanitize input data
    $name = htmlspecialchars($input_data['name']);
    $description = htmlspecialchars($input_data['description']);

    // Prepare SQL query
    $stmt = $pdo->prepare("INSERT INTO $table_name (name, description) VALUES (:name, :description)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    // Return success message
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Record created successfully']);
    exit;
}

// PUT operation
if (isset($input_data['action']) && $input_data['action'] == 'update') {
    // Validate input data
    if (!isset($input_data['id']) || !isset($input_data['name']) || !isset($input_data['description'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request data']);
        exit;
    }

    // Sanitize input data
    $id = htmlspecialchars($input_data['id']);
    $name = htmlspecialchars($input_data['name']);
    $description = htmlspecialchars($input_data['description']);

    // Check if user is admin
    if ($user_role != 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare("UPDATE $table_name SET name = :name, description = :description WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    // Return success message
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Record updated successfully']);
    exit;
}

// DELETE operation
if (isset($input_data['action']) && $input_data['action'] == 'delete') {
    // Validate input data
    if (!isset($input_data['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request data']);
        exit;
    }

    // Sanitize input data
    $id = htmlspecialchars($input_data['id']);

    // Check if user is admin
    if ($user_role != 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare("DELETE FROM $table_name WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Return success message
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Record deleted successfully']);
    exit;
}

// Return error message
http_response_code(400);
echo json_encode(['error' => 'Invalid request']);
exit;