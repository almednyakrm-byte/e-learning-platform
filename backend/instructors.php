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

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized access']);
    exit;
}

// Check if user is admin for edit and delete operations
function isAdmin() {
    return $_SESSION['user_role'] === 'admin';
}

// Handle GET requests
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Validate and sanitize input
    $instructorId = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

    // SQL query structure: Select all instructors or a specific instructor by id
    if ($instructorId) {
        $stmt = $pdo->prepare('SELECT * FROM instructors WHERE id = :id');
        $stmt->execute([':id' => $instructorId]);
        $instructors = $stmt->fetch();
    } else {
        $stmt = $pdo->prepare('SELECT * FROM instructors');
        $stmt->execute();
        $instructors = $stmt->fetchAll();
    }

    // Output processing
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($instructors);
    exit;
}

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if user is admin
    if (!isAdmin()) {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden access']);
        exit;
    }

    // Read input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $name = filter_var($data['name'] ?? null, FILTER_SANITIZE_STRING);
    $email = filter_var($data['email'] ?? null, FILTER_VALIDATE_EMAIL);

    // Check for missing fields
    if (!$name || !$email) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields']);
        exit;
    }

    // SQL query structure: Insert new instructor
    $stmt = $pdo->prepare('INSERT INTO instructors (name, email) VALUES (:name, :email)');
    $stmt->execute([':name' => $name, ':email' => $email]);

    // Output processing
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Instructor created successfully']);
    exit;
}

// Handle PUT requests
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Check if user is admin
    if (!isAdmin()) {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden access']);
        exit;
    }

    // Read input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $instructorId = filter_var($data['id'] ?? null, FILTER_VALIDATE_INT);
    $name = filter_var($data['name'] ?? null, FILTER_SANITIZE_STRING);
    $email = filter_var($data['email'] ?? null, FILTER_VALIDATE_EMAIL);

    // Check for missing fields
    if (!$instructorId || !$name || !$email) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields']);
        exit;
    }

    // SQL query structure: Update existing instructor
    $stmt = $pdo->prepare('UPDATE instructors SET name = :name, email = :email WHERE id = :id');
    $stmt->execute([':id' => $instructorId, ':name' => $name, ':email' => $email]);

    // Output processing
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Instructor updated successfully']);
    exit;
}

// Handle DELETE requests
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Check if user is admin
    if (!isAdmin()) {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden access']);
        exit;
    }

    // Read input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $instructorId = filter_var($data['id'] ?? null, FILTER_VALIDATE_INT);

    // Check for missing fields
    if (!$instructorId) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields']);
        exit;
    }

    // SQL query structure: Delete existing instructor
    $stmt = $pdo->prepare('DELETE FROM instructors WHERE id = :id');
    $stmt->execute([':id' => $instructorId]);

    // Output processing
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Instructor deleted successfully']);
    exit;
}

// Handle invalid request methods
http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);
exit;