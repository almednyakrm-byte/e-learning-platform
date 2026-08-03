<?php

require_once 'db.php';

// Get user role and ID from session
$userRole = $_SESSION['userRole'];
$userID = $_SESSION['userID'];

// Check if user is logged in
if (!$userID) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->prepare('SELECT * FROM asatidha');
    $stmt->execute();
    $asatidha = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($asatidha);
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    // Validate input
    if (!isset($input['name']) || !isset($input['email']) || !isset($input['phone'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input']);
        exit;
    }
    // Sanitize input
    $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
    $phone = filter_var($input['phone'], FILTER_SANITIZE_NUMBER_INT);
    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
    // Insert data
    $stmt = $pdo->prepare('INSERT INTO asatidha (name, email, phone) VALUES (:name, :email, :phone)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
    $stmt->execute();
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Asatidha created successfully']);
}

// Handle PUT request
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $input = json_decode(file_get_contents('php://input'), true);
    // Validate input
    if (!isset($input['id']) || !isset($input['name']) || !isset($input['email']) || !isset($input['phone'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input']);
        exit;
    }
    // Sanitize input
    $id = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);
    $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
    $phone = filter_var($input['phone'], FILTER_SANITIZE_NUMBER_INT);
    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
    // Update data
    $stmt = $pdo->prepare('UPDATE asatidha SET name = :name, email = :email, phone = :phone WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Asatidha updated successfully']);
}

// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $input = json_decode(file_get_contents('php://input'), true);
    // Validate input
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input']);
        exit;
    }
    // Sanitize input
    $id = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);
    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
    // Delete data
    $stmt = $pdo->prepare('DELETE FROM asatidha WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Asatidha deleted successfully']);
}