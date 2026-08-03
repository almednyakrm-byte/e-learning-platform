<?php
// Import database connection
require_once 'db.php';

// Initialize database connection
$dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME;
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
try {
    $pdo = new PDO($dsn, DB_USER, DB_PASSWORD, $options);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Connection failed: ' . $e->getMessage()]);
    exit;
}

// Function to check if user is logged in
function isLoggedIn() {
    // Implement your own logic to check if user is logged in
    // For demonstration purposes, assume a logged-in user
    return true;
}

// Function to check if user is admin
function isAdmin() {
    // Implement your own logic to check if user is admin
    // For demonstration purposes, assume an admin user
    return true;
}

// Handle HTTP requests
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Validate and sanitize input
    $evaluationId = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
    
    // Check if user is logged in
    if (!isLoggedIn()) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }
    
    // SQL query structure: Select all evaluations or a specific evaluation by id
    if ($evaluationId) {
        $stmt = $pdo->prepare('SELECT * FROM evaluations WHERE id = :id');
        $stmt->execute([':id' => $evaluationId]);
        $evaluation = $stmt->fetch();
        
        // Output processing: Return evaluation data in JSON format
        if ($evaluation) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($evaluation);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Evaluation not found']);
        }
    } else {
        $stmt = $pdo->prepare('SELECT * FROM evaluations');
        $stmt->execute();
        $evaluations = $stmt->fetchAll();
        
        // Output processing: Return evaluations data in JSON format
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($evaluations);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if user is logged in
    if (!isLoggedIn()) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }
    
    // Read input data
    $inputData = json_decode(file_get_contents('php://input'), true);
    
    // Validate and sanitize input data
    $evaluationData = [
        'name' => filter_var($inputData['name'] ?? null, FILTER_SANITIZE_STRING),
        'description' => filter_var($inputData['description'] ?? null, FILTER_SANITIZE_STRING),
    ];
    
    // SQL query structure: Insert new evaluation
    $stmt = $pdo->prepare('INSERT INTO evaluations (name, description) VALUES (:name, :description)');
    $stmt->execute($evaluationData);
    
    // Output processing: Return created evaluation id in JSON format
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['id' => $pdo->lastInsertId()]);
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Check if user is logged in and admin
    if (!isLoggedIn() || !isAdmin()) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }
    
    // Read input data
    $inputData = json_decode(file_get_contents('php://input'), true);
    
    // Validate and sanitize input data
    $evaluationId = filter_var($inputData['id'] ?? null, FILTER_VALIDATE_INT);
    $evaluationData = [
        'name' => filter_var($inputData['name'] ?? null, FILTER_SANITIZE_STRING),
        'description' => filter_var($inputData['description'] ?? null, FILTER_SANITIZE_STRING),
    ];
    
    // SQL query structure: Update existing evaluation
    $stmt = $pdo->prepare('UPDATE evaluations SET name = :name, description = :description WHERE id = :id');
    $stmt->execute(array_merge($evaluationData, ['id' => $evaluationId]));
    
    // Output processing: Return updated evaluation data in JSON format
    if ($stmt->rowCount() > 0) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($evaluationData);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Evaluation not found']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Check if user is logged in and admin
    if (!isLoggedIn() || !isAdmin()) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }
    
    // Read input data
    $inputData = json_decode(file_get_contents('php://input'), true);
    
    // Validate and sanitize input data
    $evaluationId = filter_var($inputData['id'] ?? null, FILTER_VALIDATE_INT);
    
    // SQL query structure: Delete existing evaluation
    $stmt = $pdo->prepare('DELETE FROM evaluations WHERE id = :id');
    $stmt->execute([':id' => $evaluationId]);
    
    // Output processing: Return deleted evaluation id in JSON format
    if ($stmt->rowCount() > 0) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['id' => $evaluationId]);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Evaluation not found']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}