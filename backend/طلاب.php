<?php
require_once 'db.php';

// Get the input data from the request body
$inputData = json_decode(file_get_contents('php://input'), true);

// Check if the user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Check if the user is an admin
if (isset($inputData['action']) && in_array($inputData['action'], array('edit', 'delete'))) {
    if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
}

// Handle GET request
if (isset($inputData['action']) && $inputData['action'] == 'get') {
    // Validate input
    if (!isset($inputData['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input
    $id = intval($inputData['id']);

    // Prepare SQL query
    $stmt = $pdo->prepare('SELECT * FROM طلاب WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Fetch data
    $data = $stmt->fetch();

    // Output data
    if ($data) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($data);
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'Not found'));
    }
} elseif (isset($inputData['action']) && $inputData['action'] == 'get_all') {
    // Prepare SQL query
    $stmt = $pdo->prepare('SELECT * FROM طلاب');
    $stmt->execute();

    // Fetch data
    $data = $stmt->fetchAll();

    // Output data
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($data);
} elseif (isset($inputData['action']) && $inputData['action'] == 'create') {
    // Validate input
    if (!isset($inputData['name']) || !isset($inputData['email'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input
    $name = trim($inputData['name']);
    $email = trim($inputData['email']);

    // Prepare SQL query
    $stmt = $pdo->prepare('INSERT INTO طلاب (name, email) VALUES (:name, :email)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    // Output data
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Created successfully'));
} elseif (isset($inputData['action']) && $inputData['action'] == 'update') {
    // Validate input
    if (!isset($inputData['id']) || !isset($inputData['name']) || !isset($inputData['email'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input
    $id = intval($inputData['id']);
    $name = trim($inputData['name']);
    $email = trim($inputData['email']);

    // Prepare SQL query
    $stmt = $pdo->prepare('UPDATE طلاب SET name = :name, email = :email WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    // Output data
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Updated successfully'));
} elseif (isset($inputData['action']) && $inputData['action'] == 'delete') {
    // Validate input
    if (!isset($inputData['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input
    $id = intval($inputData['id']);

    // Prepare SQL query
    $stmt = $pdo->prepare('DELETE FROM طلاب WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Output data
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Deleted successfully'));
} else {
    http_response_code(400);
    echo json_encode(array('error' => 'Invalid request'));
}