<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Check if input is valid
if (!$input) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid input']);
    exit;
}

// Define database table name
$table_name = 'دورات';

// Define allowed columns for CRUD operations
$allowed_columns = ['title', 'description', 'price', 'duration'];

// Define allowed roles for CRUD operations
$allowed_roles = [
    'GET' => ['admin', 'user'],
    'POST' => ['admin'],
    'PUT' => ['admin'],
    'DELETE' => ['admin']
];

// Check user role for CRUD operations
if (isset($input['action'])) {
    $action = $input['action'];
    if (!in_array($action, ['GET', 'POST', 'PUT', 'DELETE'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid action']);
        exit;
    }
    if (!in_array($_SESSION['role'], $allowed_roles[$action])) {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
}

// Handle CRUD operations
if (isset($input['action'])) {
    $action = $input['action'];
    switch ($action) {
        case 'GET':
            // Get all records
            $stmt = $pdo->prepare("SELECT * FROM $table_name");
            $stmt->execute();
            $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($records);
            break;
        case 'POST':
            // Validate and sanitize input data
            $validated_input = [];
            foreach ($allowed_columns as $column) {
                if (isset($input[$column])) {
                    $validated_input[$column] = filter_var($input[$column], FILTER_SANITIZE_STRING);
                }
            }
            // Insert new record
            $stmt = $pdo->prepare("INSERT INTO $table_name (title, description, price, duration) VALUES (:title, :description, :price, :duration)");
            $stmt->execute($validated_input);
            http_response_code(201);
            echo json_encode(['message' => 'Record created successfully']);
            break;
        case 'PUT':
            // Validate and sanitize input data
            $validated_input = [];
            foreach ($allowed_columns as $column) {
                if (isset($input[$column])) {
                    $validated_input[$column] = filter_var($input[$column], FILTER_SANITIZE_STRING);
                }
            }
            // Update existing record
            $stmt = $pdo->prepare("UPDATE $table_name SET title = :title, description = :description, price = :price, duration = :duration WHERE id = :id");
            $stmt->execute(array_merge($validated_input, ['id' => $input['id']]));
            http_response_code(200);
            echo json_encode(['message' => 'Record updated successfully']);
            break;
        case 'DELETE':
            // Delete existing record
            $stmt = $pdo->prepare("DELETE FROM $table_name WHERE id = :id");
            $stmt->execute(['id' => $input['id']]);
            http_response_code(200);
            echo json_encode(['message' => 'Record deleted successfully']);
            break;
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid input']);
}