<?php
// Import database connection
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Unauthorized access']);
    exit;
}

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Initialize database connection
$pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Handle GET requests
if ($method == 'GET') {
    // Validate and sanitize input
    $course_id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
    
    // SQL query structure
    if ($course_id) {
        $stmt = $pdo->prepare('SELECT * FROM courses WHERE id = :id');
        $stmt->bindParam(':id', $course_id);
    } else {
        $stmt = $pdo->prepare('SELECT * FROM courses');
    }
    
    // Execute query
    $stmt->execute();
    
    // Output processing
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($courses);
}

// Handle POST requests
elseif ($method == 'POST') {
    // Validate and sanitize input
    $data = json_decode(file_get_contents('php://input'), true);
    $name = filter_var($data['name'] ?? null, FILTER_SANITIZE_STRING);
    $description = filter_var($data['description'] ?? null, FILTER_SANITIZE_STRING);
    
    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden access']);
        exit;
    }
    
    // SQL query structure
    $stmt = $pdo->prepare('INSERT INTO courses (name, description) VALUES (:name, :description)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    
    // Execute query
    if ($stmt->execute()) {
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Course created successfully']);
    } else {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Failed to create course']);
    }
}

// Handle PUT requests
elseif ($method == 'PUT') {
    // Validate and sanitize input
    $data = json_decode(file_get_contents('php://input'), true);
    $course_id = filter_var($data['id'] ?? null, FILTER_VALIDATE_INT);
    $name = filter_var($data['name'] ?? null, FILTER_SANITIZE_STRING);
    $description = filter_var($data['description'] ?? null, FILTER_SANITIZE_STRING);
    
    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden access']);
        exit;
    }
    
    // SQL query structure
    $stmt = $pdo->prepare('UPDATE courses SET name = :name, description = :description WHERE id = :id');
    $stmt->bindParam(':id', $course_id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    
    // Execute query
    if ($stmt->execute()) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Course updated successfully']);
    } else {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Failed to update course']);
    }
}

// Handle DELETE requests
elseif ($method == 'DELETE') {
    // Validate and sanitize input
    $data = json_decode(file_get_contents('php://input'), true);
    $course_id = filter_var($data['id'] ?? null, FILTER_VALIDATE_INT);
    
    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden access']);
        exit;
    }
    
    // SQL query structure
    $stmt = $pdo->prepare('DELETE FROM courses WHERE id = :id');
    $stmt->bindParam(':id', $course_id);
    
    // Execute query
    if ($stmt->execute()) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Course deleted successfully']);
    } else {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Failed to delete course']);
    }
}

// Handle invalid requests
else {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Method not allowed']);
}