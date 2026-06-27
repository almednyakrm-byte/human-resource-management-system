<?php

require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get user role
$user_role = $_SESSION['user_role'];

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if user is admin for edit/deletion
    if (isset($_GET['id']) && ($user_role !== 'admin')) {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Get all jobs
    $stmt = $pdo->prepare('SELECT * FROM jobs');
    $stmt->execute();
    $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return jobs in JSON format
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($jobs);
    exit;
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get job data from request body
    $job_data = json_decode(file_get_contents('php://input'), true);

    // Validate job data
    if (!isset($job_data['title']) || !isset($job_data['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize job data
    $job_data['title'] = htmlspecialchars($job_data['title']);
    $job_data['description'] = htmlspecialchars($job_data['description']);

    // Insert job into database
    $stmt = $pdo->prepare('INSERT INTO jobs (title, description) VALUES (:title, :description)');
    $stmt->execute($job_data);

    // Return job ID in JSON format
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('id' => $pdo->lastInsertId()));
    exit;
}

// Handle PUT request
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Check if user is admin for edit
    if ($user_role !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Get job ID and data from request body
    $job_id = $_GET['id'];
    $job_data = json_decode(file_get_contents('php://input'), true);

    // Validate job data
    if (!isset($job_data['title']) || !isset($job_data['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize job data
    $job_data['title'] = htmlspecialchars($job_data['title']);
    $job_data['description'] = htmlspecialchars($job_data['description']);

    // Update job in database
    $stmt = $pdo->prepare('UPDATE jobs SET title = :title, description = :description WHERE id = :id');
    $stmt->execute($job_data);

    // Return success message in JSON format
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Job updated successfully'));
    exit;
}

// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Check if user is admin for deletion
    if ($user_role !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Get job ID from request body
    $job_id = $_GET['id'];

    // Delete job from database
    $stmt = $pdo->prepare('DELETE FROM jobs WHERE id = :id');
    $stmt->execute(array('id' => $job_id));

    // Return success message in JSON format
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Job deleted successfully'));
    exit;
}