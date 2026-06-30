<?php

// Start the session to handle user authentication
session_start();

// Include the database connection file
require_once 'db.php';

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    // If the user is logged in, return a JSON response indicating their status
    echo json_encode(array('status' => 'logged_in', 'user_id' => $_SESSION['user_id']));
    exit;
}

// Handle the login request
if (isset($_POST['action']) && $_POST['action'] == 'login') {
    // Check if the username and password are set
    if (isset($_POST['username']) && isset($_POST['password'])) {
        // Sanitize the input fields to prevent SQL injection
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        // Prepare the SQL query to select the user
        $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE username = ?");
        mysqli_stmt_bind_param($stmt, "s", $username);

        // Execute the query
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        // Fetch the user data
        $user = mysqli_fetch_assoc($result);

        // Check if the user exists and the password is correct
        if ($user && password_verify($password, $user['password'])) {
            // If the user exists and the password is correct, log them in
            $_SESSION['user_id'] = $user['id'];
            echo json_encode(array('status' => 'logged_in', 'user_id' => $_SESSION['user_id']));
        } else {
            // If the user does not exist or the password is incorrect, return an error message
            echo json_encode(array('status' => 'error', 'message' => 'Invalid username or password'));
        }
    } else {
        // If the username or password is not set, return an error message
        echo json_encode(array('status' => 'error', 'message' => 'Username and password are required'));
    }
} elseif (isset($_POST['action']) && $_POST['action'] == 'register') {
    // Check if the username, email, and password are set
    if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
        // Sanitize the input fields to prevent SQL injection
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        // Check if the username and email are valid
        if (!preg_match('/^[a-zA-Z0-9]+$/', $username)) {
            // If the username is not valid, return an error message
            echo json_encode(array('status' => 'error', 'message' => 'Invalid username'));
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // If the email is not valid, return an error message
            echo json_encode(array('status' => 'error', 'message' => 'Invalid email'));
            exit;
        }

        // Check if the password is strong enough
        if (strlen($password) < 8) {
            // If the password is not strong enough, return an error message
            echo json_encode(array('status' => 'error', 'message' => 'Password must be at least 8 characters long'));
            exit;
        }

        // Hash the password using password_hash()
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare the SQL query to insert the user
        $stmt = mysqli_prepare($conn, "INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashed_password);

        // Execute the query
        mysqli_stmt_execute($stmt);

        // Check if the user was inserted successfully
        if (mysqli_stmt_affected_rows($stmt) == 1) {
            // If the user was inserted successfully, log them in
            $_SESSION['user_id'] = mysqli_insert_id($conn);
            echo json_encode(array('status' => 'logged_in', 'user_id' => $_SESSION['user_id']));
        } else {
            // If the user was not inserted successfully, return an error message
            echo json_encode(array('status' => 'error', 'message' => 'Failed to register user'));
        }
    } else {
        // If the username, email, or password is not set, return an error message
        echo json_encode(array('status' => 'error', 'message' => 'Username, email, and password are required'));
    }
}

// Handle the logout request
if (isset($_POST['action']) && $_POST['action'] == 'logout') {
    // Destroy the session to log the user out
    session_destroy();
    echo json_encode(array('status' => 'logged_out'));
}

// Handle the get request to check the session status
if (isset($_GET['action']) && $_GET['action'] == 'status') {
    // Check if the user is logged in
    if (isset($_SESSION['user_id'])) {
        // If the user is logged in, return a JSON response indicating their status
        echo json_encode(array('status' => 'logged_in', 'user_id' => $_SESSION['user_id']));
    } else {
        // If the user is not logged in, return a JSON response indicating their status
        echo json_encode(array('status' => 'logged_out'));
    }
}

// Close the database connection
mysqli_close($conn);

?>