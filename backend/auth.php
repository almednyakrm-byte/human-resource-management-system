<?php
// Start the session to handle user authentication
session_start();

// Include database connection file
require_once 'db.php';

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    // If user is logged in, return JSON response with user data
    $user_id = $_SESSION['user_id'];
    $query = "SELECT * FROM users WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    echo json_encode(array('status' => 'logged_in', 'user' => $user));
    exit;
}

// Handle login request
if (isset($_POST['action']) && $_POST['action'] == 'login') {
    // Check if username and password are provided
    if (!isset($_POST['username']) || !isset($_POST['password'])) {
        echo json_encode(array('status' => 'error', 'message' => 'Username and password are required'));
        exit;
    }

    // Sanitize and validate username and password
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

    // Prepare SQL query to check user credentials
    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Check if user exists and password is correct
    if ($user && password_verify($password, $user['password'])) {
        // Login successful, store user ID in session
        $_SESSION['user_id'] = $user['id'];
        echo json_encode(array('status' => 'logged_in'));
    } else {
        // Login failed, return error message
        echo json_encode(array('status' => 'error', 'message' => 'Invalid username or password'));
    }
    exit;
}

// Handle register request
if (isset($_POST['action']) && $_POST['action'] == 'register') {
    // Check if username, email, and password are provided
    if (!isset($_POST['username']) || !isset($_POST['email']) || !isset($_POST['password'])) {
        echo json_encode(array('status' => 'error', 'message' => 'Username, email, and password are required'));
        exit;
    }

    // Sanitize and validate username, email, and password
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

    // Prepare SQL query to check if username or email already exists
    $query = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Check if username or email already exists
    if ($user) {
        // Username or email already exists, return error message
        echo json_encode(array('status' => 'error', 'message' => 'Username or email already exists'));
        exit;
    }

    // Hash password using password_hash()
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare SQL query to insert new user
    $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("sss", $username, $email, $hashed_password);
    $stmt->execute();

    // Login new user
    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $_SESSION['user_id'] = $user['id'];
    echo json_encode(array('status' => 'logged_in'));
    exit;
}

// Handle logout request
if (isset($_POST['action']) && $_POST['action'] == 'logout') {
    // Destroy session to log out user
    session_destroy();
    echo json_encode(array('status' => 'logged_out'));
    exit;
}


This code handles user registration, login, logout, and checks the current session user status. It uses prepared statements to prevent SQL injection and password_hash() to securely store passwords. It also uses JSON responses for AJAX calls and includes input field validation and sanitization.