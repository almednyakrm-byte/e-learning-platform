<?php

// Import database connection settings
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

// Validate input data
if (!$input) {
    http_response_code(400);
    echo json_encode(array('error' => 'Invalid request'));
    exit;
}

// Validate student data
if (isset($input['id'])) {
    if (!is_numeric($input['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid student ID'));
        exit;
    }
}

if (isset($input['name']) && !is_string($input['name'])) {
    http_response_code(400);
    echo json_encode(array('error' => 'Invalid student name'));
    exit;
}

if (isset($input['email']) && !is_string($input['email'])) {
    http_response_code(400);
    echo json_encode(array('error' => 'Invalid student email'));
    exit;
}

// Connect to database
$db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Get all students
    $stmt = $db->prepare('SELECT * FROM students');
    $stmt->execute();
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($students);
    exit;
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate student data
    if (!isset($input['name']) || !isset($input['email'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Missing student data'));
        exit;
    }

    // Sanitize student data
    $name = $db->quote($input['name']);
    $email = $db->quote($input['email']);

    // Insert student
    $stmt = $db->prepare('INSERT INTO students (name, email) VALUES (:name, :email)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Student created successfully'));
    exit;
}

// Handle PUT request
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    // Validate student ID
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Missing student ID'));
        exit;
    }

    // Validate student data
    if (!isset($input['name']) || !isset($input['email'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Missing student data'));
        exit;
    }

    // Sanitize student data
    $name = $db->quote($input['name']);
    $email = $db->quote($input['email']);

    // Update student
    $stmt = $db->prepare('UPDATE students SET name = :name, email = :email WHERE id = :id');
    $stmt->bindParam(':id', $input['id']);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Student updated successfully'));
    exit;
}

// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    // Validate student ID
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Missing student ID'));
        exit;
    }

    // Delete student
    $stmt = $db->prepare('DELETE FROM students WHERE id = :id');
    $stmt->bindParam(':id', $input['id']);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Student deleted successfully'));
    exit;
}

// Handle unknown request method
http_response_code(405);
echo json_encode(array('error' => 'Method not allowed'));
exit;

?>