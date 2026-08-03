<?php

require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$inputData = json_decode(file_get_contents('php://input'), true);

// Check if user is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    // Allow read operations for non-admin users
    if (isset($inputData['action']) && in_array($inputData['action'], array('GET', 'POST'))) {
        // Process read operation
    } else {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
}

// Process CRUD operations
if (isset($inputData['action'])) {
    switch ($inputData['action']) {
        case 'GET':
            // Get all materials
            $stmt = $pdo->prepare("SELECT * FROM materials");
            $stmt->execute();
            $materials = $stmt->fetchAll(PDO::FETCH_ASSOC);
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($materials);
            break;
        case 'POST':
            // Create new material
            if (isset($inputData['name']) && isset($inputData['description'])) {
                $name = filter_var($inputData['name'], FILTER_SANITIZE_STRING);
                $description = filter_var($inputData['description'], FILTER_SANITIZE_STRING);
                $stmt = $pdo->prepare("INSERT INTO materials (name, description) VALUES (:name, :description)");
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':description', $description);
                $stmt->execute();
                http_response_code(201);
                echo json_encode(array('message' => 'Material created successfully'));
            } else {
                http_response_code(400);
                echo json_encode(array('error' => 'Invalid request'));
            }
            break;
        case 'PUT':
            // Update existing material
            if (isset($inputData['id']) && isset($inputData['name']) && isset($inputData['description'])) {
                $id = filter_var($inputData['id'], FILTER_SANITIZE_NUMBER_INT);
                $name = filter_var($inputData['name'], FILTER_SANITIZE_STRING);
                $description = filter_var($inputData['description'], FILTER_SANITIZE_STRING);
                $stmt = $pdo->prepare("UPDATE materials SET name = :name, description = :description WHERE id = :id");
                $stmt->bindParam(':id', $id);
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':description', $description);
                $stmt->execute();
                http_response_code(200);
                echo json_encode(array('message' => 'Material updated successfully'));
            } else {
                http_response_code(400);
                echo json_encode(array('error' => 'Invalid request'));
            }
            break;
        case 'DELETE':
            // Delete material
            if (isset($inputData['id'])) {
                $id = filter_var($inputData['id'], FILTER_SANITIZE_NUMBER_INT);
                $stmt = $pdo->prepare("DELETE FROM materials WHERE id = :id");
                $stmt->bindParam(':id', $id);
                $stmt->execute();
                http_response_code(200);
                echo json_encode(array('message' => 'Material deleted successfully'));
            } else {
                http_response_code(400);
                echo json_encode(array('error' => 'Invalid request'));
            }
            break;
        default:
            http_response_code(405);
            echo json_encode(array('error' => 'Method not allowed'));
            break;
    }
} else {
    http_response_code(400);
    echo json_encode(array('error' => 'Invalid request'));
}