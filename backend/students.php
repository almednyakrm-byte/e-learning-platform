<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Initialize database connection
$dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME;
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
$pdo = new PDO($dsn, DB_USER, DB_PASSWORD, $options);

// Handle GET requests
if ($method === 'GET') {
    // Validate and sanitize input
    $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
    if ($id === null || $id < 1) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid student ID']);
        exit;
    }

    // Prepare and execute SQL query
    $stmt = $pdo->prepare('SELECT * FROM students WHERE id = :id');
    $stmt->execute([':id' => $id]);
    $student = $stmt->fetch();

    // Process output
    if ($student === false) {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Student not found']);
    } else {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($student);
    }
    exit;
}

// Handle POST requests
if ($method === 'POST') {
    // Validate and sanitize input
    $input = json_decode(file_get_contents('php://input'), true);
    if ($input === null) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid JSON input']);
        exit;
    }

    $name = filter_var($input['name'] ?? null, FILTER_SANITIZE_STRING);
    $email = filter_var($input['email'] ?? null, FILTER_SANITIZE_EMAIL);
    if ($name === null || $email === null) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Name and email are required']);
        exit;
    }

    // Prepare and execute SQL query
    $stmt = $pdo->prepare('INSERT INTO students (name, email) VALUES (:name, :email)');
    $stmt->execute([':name' => $name, ':email' => $email]);
    $id = $pdo->lastInsertId();

    // Process output
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['id' => $id, 'name' => $name, 'email' => $email]);
    exit;
}

// Handle PUT requests
if ($method === 'PUT') {
    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Validate and sanitize input
    $input = json_decode(file_get_contents('php://input'), true);
    if ($input === null) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid JSON input']);
        exit;
    }

    $id = filter_var($input['id'] ?? null, FILTER_VALIDATE_INT);
    $name = filter_var($input['name'] ?? null, FILTER_SANITIZE_STRING);
    $email = filter_var($input['email'] ?? null, FILTER_SANITIZE_EMAIL);
    if ($id === null || $name === null || $email === null) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'ID, name, and email are required']);
        exit;
    }

    // Prepare and execute SQL query
    $stmt = $pdo->prepare('UPDATE students SET name = :name, email = :email WHERE id = :id');
    $stmt->execute([':id' => $id, ':name' => $name, ':email' => $email]);
    $rows = $stmt->rowCount();

    // Process output
    if ($rows === 0) {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Student not found']);
    } else {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['id' => $id, 'name' => $name, 'email' => $email]);
    }
    exit;
}

// Handle DELETE requests
if ($method === 'DELETE') {
    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Validate and sanitize input
    $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
    if ($id === null || $id < 1) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid student ID']);
        exit;
    }

    // Prepare and execute SQL query
    $stmt = $pdo->prepare('DELETE FROM students WHERE id = :id');
    $stmt->execute([':id' => $id]);
    $rows = $stmt->rowCount();

    // Process output
    if ($rows === 0) {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Student not found']);
    } else {
        http_response_code(204);
        header('Content-Type: application/json');
    }
    exit;
}

// Handle unknown methods
http_response_code(405);
header('Content-Type: application/json');
echo json_encode(['error' => 'Method not allowed']);