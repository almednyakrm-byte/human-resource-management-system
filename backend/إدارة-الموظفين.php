<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if (isset($_GET['action']) && $_GET['action'] == 'get_all') {
    // Check if user is admin
    if ($_SESSION['role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    try {
        // Prepare SQL query to select all employees
        $stmt = $pdo->prepare("SELECT * FROM employees");
        $stmt->execute();
        $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Return employees in JSON format
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($employees);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
} elseif (isset($_GET['action']) && $_GET['action'] == 'get_one') {
    // Check if user is admin
    if ($_SESSION['role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    try {
        // Prepare SQL query to select one employee by ID
        $stmt = $pdo->prepare("SELECT * FROM employees WHERE id = :id");
        $stmt->bindParam(':id', $_GET['id']);
        $stmt->execute();
        $employee = $stmt->fetch(PDO::FETCH_ASSOC);

        // Return employee in JSON format
        if ($employee) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($employee);
        } else {
            http_response_code(404);
            echo json_encode(array('error' => 'Not Found'));
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
} elseif (isset($_GET['action']) && $_GET['action'] == 'get_by_name') {
    try {
        // Prepare SQL query to select employees by name
        $stmt = $pdo->prepare("SELECT * FROM employees WHERE name LIKE :name");
        $stmt->bindParam(':name', '%' . $_GET['name'] . '%');
        $stmt->execute();
        $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Return employees in JSON format
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($employees);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
} elseif (isset($_GET['action']) && $_GET['action'] == 'get_by_department') {
    try {
        // Prepare SQL query to select employees by department
        $stmt = $pdo->prepare("SELECT * FROM employees WHERE department = :department");
        $stmt->bindParam(':department', $_GET['department']);
        $stmt->execute();
        $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Return employees in JSON format
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($employees);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
} elseif (isset($_GET['action']) && $_GET['action'] == 'get_by_position') {
    try {
        // Prepare SQL query to select employees by position
        $stmt = $pdo->prepare("SELECT * FROM employees WHERE position = :position");
        $stmt->bindParam(':position', $_GET['position']);
        $stmt->execute();
        $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Return employees in JSON format
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($employees);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }

// Handle POST request
} elseif (isset($_POST['action']) && $_POST['action'] == 'create') {
    // Validate input data
    if (!isset($input['name']) || !isset($input['department']) || !isset($input['position'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Bad Request'));
        exit;
    }

    // Sanitize input data
    $name = htmlspecialchars($input['name']);
    $department = htmlspecialchars($input['department']);
    $position = htmlspecialchars($input['position']);

    try {
        // Prepare SQL query to insert new employee
        $stmt = $pdo->prepare("INSERT INTO employees (name, department, position) VALUES (:name, :department, :position)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':department', $department);
        $stmt->bindParam(':position', $position);
        $stmt->execute();

        // Return employee ID in JSON format
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(array('id' => $pdo->lastInsertId()));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
} elseif (isset($_POST['action']) && $_POST['action'] == 'update') {
    // Check if user is admin
    if ($_SESSION['role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Validate input data
    if (!isset($input['id']) || !isset($input['name']) || !isset($input['department']) || !isset($input['position'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Bad Request'));
        exit;
    }

    // Sanitize input data
    $id = htmlspecialchars($input['id']);
    $name = htmlspecialchars($input['name']);
    $department = htmlspecialchars($input['department']);
    $position = htmlspecialchars($input['position']);

    try {
        // Prepare SQL query to update employee
        $stmt = $pdo->prepare("UPDATE employees SET name = :name, department = :department, position = :position WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':department', $department);
        $stmt->bindParam(':position', $position);
        $stmt->execute();

        // Return success message in JSON format
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Employee updated successfully'));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
} elseif (isset($_POST['action']) && $_POST['action'] == 'delete') {
    // Check if user is admin
    if ($_SESSION['role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Validate input data
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Bad Request'));
        exit;
    }

    // Sanitize input data
    $id = htmlspecialchars($input['id']);

    try {
        // Prepare SQL query to delete employee
        $stmt = $pdo->prepare("DELETE FROM employees WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // Return success message in JSON format
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Employee deleted successfully'));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
} else {
    http_response_code(404);
    echo json_encode(array('error' => 'Not Found'));
}
?>