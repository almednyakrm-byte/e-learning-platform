<?php
// Start the session to handle user authentication
session_start();

// Include the database connection file
require_once 'db.php';

// Define a function to check if the user is logged in
function isLoggedIn() {
    // Check if the user is logged in by checking the session variable
    return isset($_SESSION['user_id']) ? true : false;
}

// Define a function to register a new user
function registerUser() {
    // Check if the user is already logged in
    if (isLoggedIn()) {
        echo json_encode(array('error' => 'You are already logged in'));
        return;
    }

    // Check if the input fields are set
    if (!isset($_POST['username']) || !isset($_POST['email']) || !isset($_POST['password'])) {
        echo json_encode(array('error' => 'Please fill in all fields'));
        return;
    }

    // Sanitize the input fields
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

    // Check if the input fields are valid
    if (empty($username) || empty($email) || empty($password)) {
        echo json_encode(array('error' => 'Please fill in all fields'));
        return;
    }

    // Check if the email is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(array('error' => 'Invalid email address'));
        return;
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query to insert the new user
    $stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hashedPassword);

    // Execute the query
    if ($stmt->execute()) {
        echo json_encode(array('success' => 'User registered successfully'));
    } else {
        echo json_encode(array('error' => 'Failed to register user'));
    }

    // Close the statement
    $stmt->close();
}

// Define a function to login a user
function loginUser() {
    // Check if the user is already logged in
    if (isLoggedIn()) {
        echo json_encode(array('error' => 'You are already logged in'));
        return;
    }

    // Check if the input fields are set
    if (!isset($_POST['username']) || !isset($_POST['password'])) {
        echo json_encode(array('error' => 'Please fill in all fields'));
        return;
    }

    // Sanitize the input fields
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

    // Check if the input fields are valid
    if (empty($username) || empty($password)) {
        echo json_encode(array('error' => 'Please fill in all fields'));
        return;
    }

    // Prepare the SQL query to select the user
    $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);

    // Execute the query
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user exists
    if ($result->num_rows == 0) {
        echo json_encode(array('error' => 'Invalid username or password'));
        return;
    }

    // Fetch the user data
    $user = $result->fetch_assoc();

    // Check if the password is correct
    if (!password_verify($password, $user['password'])) {
        echo json_encode(array('error' => 'Invalid username or password'));
        return;
    }

    // Login the user
    $_SESSION['user_id'] = $user['id'];
    echo json_encode(array('success' => 'User logged in successfully'));
}

// Define a function to logout a user
function logoutUser() {
    // Check if the user is logged in
    if (!isLoggedIn()) {
        echo json_encode(array('error' => 'You are not logged in'));
        return;
    }

    // Logout the user
    session_unset();
    session_destroy();
    echo json_encode(array('success' => 'User logged out successfully'));
}

// Handle AJAX requests
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'register':
                registerUser();
                break;
            case 'login':
                loginUser();
                break;
            case 'logout':
                logoutUser();
                break;
            case 'checkSession':
                echo json_encode(array('isLoggedIn' => isLoggedIn()));
                break;
        }
    }
}


This code includes the following security features:

*   **Input Validation**: The code checks if the input fields are set and valid before processing them. This prevents SQL injection and cross-site scripting (XSS) attacks.
*   **Password Hashing**: The code uses the `password_hash()` function to hash the user's password before storing it in the database. This prevents password disclosure in case of a database breach.
*   **Prepared Statements**: The code uses prepared statements to execute SQL queries. This prevents SQL injection attacks by separating the SQL code from the user input.
*   **Session Handling**: The code uses the `session_start()` function to start a session and the `session_unset()` and `session_destroy()` functions to logout the user. This ensures that the user's session is properly managed and secure.