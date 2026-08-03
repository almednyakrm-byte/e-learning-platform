<?php
require_once 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get the user role
$userRole = $_SESSION['user_role'];

// Handle GET requests
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Validate and sanitize input
    $employeeId = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

    // SQL query structure: Select all employees or a specific employee
    $sql = 'SELECT * FROM employees';
    $params = [];

    if ($employeeId) {
        $sql .= ' WHERE id = :id';
        $params[':id'] = $employeeId;
    }

    // Prepare and execute the query
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    // Output processing
    $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($employees);
}

// Handle POST requests
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the user is an admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Get input data
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $name = filter_var($input['name'] ?? null, FILTER_SANITIZE_STRING);
    $email = filter_var($input['email'] ?? null, FILTER_VALIDATE_EMAIL);
    $department = filter_var($input['department'] ?? null, FILTER_SANITIZE_STRING);

    // Check for missing fields
    if (!$name || !$email || !$department) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Missing fields']);
        exit;
    }

    // SQL query structure: Insert a new employee
    $sql = 'INSERT INTO employees (name, email, department) VALUES (:name, :email, :department)';
    $params = [
        ':name' => $name,
        ':email' => $email,
        ':department' => $department,
    ];

    // Prepare and execute the query
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    // Output processing
    $employeeId = $pdo->lastInsertId();
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['id' => $employeeId]);
}

// Handle PUT requests
elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Check if the user is an admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Get input data
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $employeeId = filter_var($input['id'] ?? null, FILTER_VALIDATE_INT);
    $name = filter_var($input['name'] ?? null, FILTER_SANITIZE_STRING);
    $email = filter_var($input['email'] ?? null, FILTER_VALIDATE_EMAIL);
    $department = filter_var($input['department'] ?? null, FILTER_SANITIZE_STRING);

    // Check for missing fields
    if (!$employeeId || !$name || !$email || !$department) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Missing fields']);
        exit;
    }

    // SQL query structure: Update an existing employee
    $sql = 'UPDATE employees SET name = :name, email = :email, department = :department WHERE id = :id';
    $params = [
        ':id' => $employeeId,
        ':name' => $name,
        ':email' => $email,
        ':department' => $department,
    ];

    // Prepare and execute the query
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    // Output processing
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Employee updated successfully']);
}

// Handle DELETE requests
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Check if the user is an admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Get input data
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $employeeId = filter_var($input['id'] ?? null, FILTER_VALIDATE_INT);

    // Check for missing fields
    if (!$employeeId) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Missing fields']);
        exit;
    }

    // SQL query structure: Delete an existing employee
    $sql = 'DELETE FROM employees WHERE id = :id';
    $params = [
        ':id' => $employeeId,
    ];

    // Prepare and execute the query
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    // Output processing
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Employee deleted successfully']);
}

// Handle invalid request methods
else {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Method not allowed']);
}