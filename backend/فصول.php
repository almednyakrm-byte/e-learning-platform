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

// Handle GET request
if (isset($_GET['id'])) {
    // Get a single record by ID
    $stmt = $pdo->prepare('SELECT * FROM فصول WHERE id = :id');
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->execute();
    $record = $stmt->fetch();
    if ($record) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($record);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Record not found']);
    }
} elseif (isset($_GET['all'])) {
    // Get all records
    $stmt = $pdo->prepare('SELECT * FROM فصول');
    $stmt->execute();
    $records = $stmt->fetchAll();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($records);
} else {
    // Handle POST, PUT, DELETE requests
    if (isset($input['id'])) {
        // Update a record
        if ($_SESSION['role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden']);
            exit;
        }
        $stmt = $pdo->prepare('UPDATE فصول SET name = :name, description = :description WHERE id = :id');
        $stmt->bindParam(':id', $input['id']);
        $stmt->bindParam(':name', $input['name']);
        $stmt->bindParam(':description', $input['description']);
        if ($stmt->execute()) {
            http_response_code(200);
            echo json_encode(['message' => 'Record updated successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Internal Server Error']);
        }
    } elseif (isset($input['name']) && isset($input['description'])) {
        // Insert a new record
        $stmt = $pdo->prepare('INSERT INTO فصول (name, description) VALUES (:name, :description)');
        $stmt->bindParam(':name', $input['name']);
        $stmt->bindParam(':description', $input['description']);
        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode(['message' => 'Record created successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Internal Server Error']);
        }
    } elseif (isset($input['id'])) {
        // Delete a record
        if ($_SESSION['role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden']);
            exit;
        }
        $stmt = $pdo->prepare('DELETE FROM فصول WHERE id = :id');
        $stmt->bindParam(':id', $input['id']);
        if ($stmt->execute()) {
            http_response_code(200);
            echo json_encode(['message' => 'Record deleted successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Internal Server Error']);
        }
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Bad Request']);
    }
}