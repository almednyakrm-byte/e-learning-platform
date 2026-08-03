<?php

require_once 'db.php';

// Get user role and ID from session
$userRole = $_SESSION['userRole'];
$userID = $_SESSION['userID'];

// Check if user is logged in
if (!$userID) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Validate and sanitize input
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    // Check if admin
    if ($userRole !== 'admin' && $id) {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('SELECT * FROM معلمين');
    $stmt->execute();

    // Fetch and return data
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Read JSON input
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
    $phone = filter_var($input['phone'], FILTER_SANITIZE_NUMBER_INT);

    // Check if admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('INSERT INTO معلمين (name, email, phone) VALUES (:name, :email, :phone)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
    $stmt->execute();

    // Return success response
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Teacher added successfully'));
    exit;
}

// Handle PUT request
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Read JSON input
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $id = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);
    $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
    $phone = filter_var($input['phone'], FILTER_SANITIZE_NUMBER_INT);

    // Check if admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('UPDATE معلمين SET name = :name, email = :email, phone = :phone WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
    $stmt->execute();

    // Return success response
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Teacher updated successfully'));
    exit;
}

// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Validate and sanitize input
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    // Check if admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('DELETE FROM معلمين WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Return success response
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Teacher deleted successfully'));
    exit;
}

// Return error response for invalid request method
http_response_code(405);
echo json_encode(array('error' => 'Method Not Allowed'));
exit;