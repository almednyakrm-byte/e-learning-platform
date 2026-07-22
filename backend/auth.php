<?php
// Start the session to store user data
session_start();

// Import database connection settings
require_once 'db.php';

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    // If user is logged in, return JSON response with user data
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];
    $response = array(
        'status' => 'success',
        'message' => 'User is already logged in',
        'user_id' => $user_id,
        'username' => $username
    );
    echo json_encode($response);
    exit;
}

// Handle login request
if (isset($_POST['action']) && $_POST['action'] == 'login') {
    // Check if required fields are set
    if (!isset($_POST['username']) || !isset($_POST['password'])) {
        $response = array(
            'status' => 'error',
            'message' => 'Please fill in all fields'
        );
        echo json_encode($response);
        exit;
    }

    // Sanitize input fields
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

    // Prepare SQL query to select user by username
    $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    // Fetch user data from database
    $user = $stmt->fetch();

    // Check if user exists and password is correct
    if ($user && password_verify($password, $user['password'])) {
        // If user is valid, store user data in session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $response = array(
            'status' => 'success',
            'message' => 'User logged in successfully'
        );
        echo json_encode($response);
        exit;
    } else {
        // If user is not valid, return error response
        $response = array(
            'status' => 'error',
            'message' => 'Invalid username or password'
        );
        echo json_encode($response);
        exit;
    }
}

// Handle register request
if (isset($_POST['action']) && $_POST['action'] == 'register') {
    // Check if required fields are set
    if (!isset($_POST['username']) || !isset($_POST['email']) || !isset($_POST['password']) || !isset($_POST['confirm_password'])) {
        $response = array(
            'status' => 'error',
            'message' => 'Please fill in all fields'
        );
        echo json_encode($response);
        exit;
    }

    // Sanitize input fields
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
    $confirm_password = filter_var($_POST['confirm_password'], FILTER_SANITIZE_STRING);

    // Check if password and confirm password match
    if ($password !== $confirm_password) {
        $response = array(
            'status' => 'error',
            'message' => 'Passwords do not match'
        );
        echo json_encode($response);
        exit;
    }

    // Hash password using password_hash()
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare SQL query to insert new user
    $stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashed_password);
    $stmt->execute();

    // Return success response
    $response = array(
        'status' => 'success',
        'message' => 'User registered successfully'
    );
    echo json_encode($response);
    exit;
}

// Handle logout request
if (isset($_POST['action']) && $_POST['action'] == 'logout') {
    // Destroy session to log user out
    session_destroy();
    $response = array(
        'status' => 'success',
        'message' => 'User logged out successfully'
    );
    echo json_encode($response);
    exit;
}
?>