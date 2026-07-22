<?php
// Import database connection
require_once 'db.php';

// Initialize database connection
$pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Function to check if user is logged in
function isLoggedIn() {
    // Replace with actual session or token validation logic
    return isset($_SESSION['user_id']);
}

// Function to check if user is admin
function isAdmin() {
    // Replace with actual session or token validation logic
    return isset($_SESSION['is_admin']);
}

// Handle GET requests
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Validate and sanitize input
    $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

    // Check if user is logged in
    if (!isLoggedIn()) {
        http_response_code(401);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // SQL query structure: Select all tests or a specific test by id
    if ($id) {
        $stmt = $pdo->prepare('SELECT * FROM tests WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $tests = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        $stmt = $pdo->prepare('SELECT * FROM tests');
        $stmt->execute();
        $tests = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Output processing
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($tests);
}

// Handle POST requests
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if user is logged in
    if (!isLoggedIn()) {
        http_response_code(401);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // Read input from request body
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $name = filter_var($input['name'] ?? null, FILTER_SANITIZE_STRING);
    $description = filter_var($input['description'] ?? null, FILTER_SANITIZE_STRING);

    // Check for required fields
    if (!$name || !$description) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Name and description are required']);
        exit;
    }

    // SQL query structure: Insert new test
    $stmt = $pdo->prepare('INSERT INTO tests (name, description) VALUES (:name, :description)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    // Output processing
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Test created successfully']);
}

// Handle PUT requests
elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Check if user is logged in and admin
    if (!isLoggedIn() || !isAdmin()) {
        http_response_code(401);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // Read input from request body
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $id = filter_var($input['id'] ?? null, FILTER_VALIDATE_INT);
    $name = filter_var($input['name'] ?? null, FILTER_SANITIZE_STRING);
    $description = filter_var($input['description'] ?? null, FILTER_SANITIZE_STRING);

    // Check for required fields
    if (!$id || !$name || !$description) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Id, name, and description are required']);
        exit;
    }

    // SQL query structure: Update existing test
    $stmt = $pdo->prepare('UPDATE tests SET name = :name, description = :description WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    // Output processing
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Test updated successfully']);
}

// Handle DELETE requests
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Check if user is logged in and admin
    if (!isLoggedIn() || !isAdmin()) {
        http_response_code(401);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // Read input from request body
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $id = filter_var($input['id'] ?? null, FILTER_VALIDATE_INT);

    // Check for required fields
    if (!$id) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Id is required']);
        exit;
    }

    // SQL query structure: Delete existing test
    $stmt = $pdo->prepare('DELETE FROM tests WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Output processing
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Test deleted successfully']);
}

// Handle invalid request methods
else {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Method not allowed']);
}