<?php
// Import database connection file
require_once 'db.php';

// Initialize database connection
$pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Function to validate user role
function validateUserRole($role) {
    // For demonstration purposes, assume a logged-in user with an 'admin' role
    // In a real application, you would retrieve the user's role from a session or database
    $loggedInUserRole = 'admin'; // Replace with actual user role retrieval logic
    return $role === $loggedInUserRole;
}

// Handle GET requests
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Validate user role
    if (!validateUserRole('admin') && !validateUserRole('user')) {
        http_response_code(401);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // Prepare SQL query to retrieve all tests
    $stmt = $pdo->prepare('SELECT * FROM tests');
    $stmt->execute();

    // Fetch results
    $tests = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return results as JSON
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($tests);
}

// Handle POST requests
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate user role
    if (!validateUserRole('admin')) {
        http_response_code(401);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // Read input data
    $inputData = json_decode(file_get_contents('php://input'), true);

    // Validate input data
    if (empty($inputData['name']) || empty($inputData['description'])) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid input data']);
        exit;
    }

    // Prepare SQL query to insert new test
    $stmt = $pdo->prepare('INSERT INTO tests (name, description) VALUES (:name, :description)');
    $stmt->bindParam(':name', $inputData['name']);
    $stmt->bindParam(':description', $inputData['description']);

    // Execute query
    if ($stmt->execute()) {
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Test created successfully']);
    } else {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Failed to create test']);
    }
}

// Handle PUT requests
elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Validate user role
    if (!validateUserRole('admin')) {
        http_response_code(401);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // Read input data
    $inputData = json_decode(file_get_contents('php://input'), true);

    // Validate input data
    if (empty($inputData['id']) || empty($inputData['name']) || empty($inputData['description'])) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid input data']);
        exit;
    }

    // Prepare SQL query to update existing test
    $stmt = $pdo->prepare('UPDATE tests SET name = :name, description = :description WHERE id = :id');
    $stmt->bindParam(':id', $inputData['id']);
    $stmt->bindParam(':name', $inputData['name']);
    $stmt->bindParam(':description', $inputData['description']);

    // Execute query
    if ($stmt->execute()) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Test updated successfully']);
    } else {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Failed to update test']);
    }
}

// Handle DELETE requests
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Validate user role
    if (!validateUserRole('admin')) {
        http_response_code(401);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // Read input data
    $inputData = json_decode(file_get_contents('php://input'), true);

    // Validate input data
    if (empty($inputData['id'])) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid input data']);
        exit;
    }

    // Prepare SQL query to delete existing test
    $stmt = $pdo->prepare('DELETE FROM tests WHERE id = :id');
    $stmt->bindParam(':id', $inputData['id']);

    // Execute query
    if ($stmt->execute()) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Test deleted successfully']);
    } else {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Failed to delete test']);
    }
}

// Handle invalid request methods
else {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Method not allowed']);
}