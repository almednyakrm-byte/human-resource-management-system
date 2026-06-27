<?php

require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Check if user is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    http_response_code(403);
    echo json_encode(array('error' => 'Forbidden'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if (isset($_GET['action']) && $_GET['action'] == 'get_all') {
    // Validate input
    if (!isset($input['limit']) || !isset($input['offset'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input'));
        exit;
    }

    // Sanitize input
    $limit = (int) $input['limit'];
    $offset = (int) $input['offset'];

    // SQL query
    $stmt = $pdo->prepare('SELECT * FROM الموظفين ORDER BY id LIMIT :limit OFFSET :offset');
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    // Fetch data
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Output
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($data);
} elseif (isset($_GET['action']) && $_GET['action'] == 'get_one') {
    // Validate input
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input'));
        exit;
    }

    // Sanitize input
    $id = (int) $input['id'];

    // SQL query
    $stmt = $pdo->prepare('SELECT * FROM الموظفين WHERE id = :id');
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    // Fetch data
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    // Output
    if ($data) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($data);
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'Not found'));
    }
} elseif (isset($_GET['action']) && $_GET['action'] == 'get_count') {
    // SQL query
    $stmt = $pdo->query('SELECT COUNT(*) as count FROM الموظفين');
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    // Output
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($data);
}

// Handle POST request
elseif (isset($_GET['action']) && $_GET['action'] == 'create') {
    // Validate input
    if (!isset($input['name']) || !isset($input['email']) || !isset($input['phone'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input'));
        exit;
    }

    // Sanitize input
    $name = trim($input['name']);
    $email = trim($input['email']);
    $phone = trim($input['phone']);

    // SQL query
    $stmt = $pdo->prepare('INSERT INTO الموظفين (name, email, phone) VALUES (:name, :email, :phone)');
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
    $stmt->execute();

    // Output
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Created successfully'));
}

// Handle PUT request
elseif (isset($_GET['action']) && $_GET['action'] == 'update') {
    // Validate input
    if (!isset($input['id']) || !isset($input['name']) || !isset($input['email']) || !isset($input['phone'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input'));
        exit;
    }

    // Sanitize input
    $id = (int) $input['id'];
    $name = trim($input['name']);
    $email = trim($input['email']);
    $phone = trim($input['phone']);

    // SQL query
    $stmt = $pdo->prepare('UPDATE الموظفين SET name = :name, email = :email, phone = :phone WHERE id = :id');
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
    $stmt->execute();

    // Output
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Updated successfully'));
}

// Handle DELETE request
elseif (isset($_GET['action']) && $_GET['action'] == 'delete') {
    // Validate input
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input'));
        exit;
    }

    // Sanitize input
    $id = (int) $input['id'];

    // SQL query
    $stmt = $pdo->prepare('DELETE FROM الموظفين WHERE id = :id');
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    // Output
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Deleted successfully'));
}

// Output error
else {
    http_response_code(405);
    echo json_encode(array('error' => 'Method not allowed'));
}