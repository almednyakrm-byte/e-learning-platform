<?php

require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Validate and sanitize input
    $limit = isset($input['limit']) ? intval($input['limit']) : 10;
    $offset = isset($input['offset']) ? intval($input['offset']) : 0;
    $sort = isset($input['sort']) ? $input['sort'] : 'id';
    $order = isset($input['order']) ? $input['order'] : 'ASC';

    // Prepare SQL query
    $stmt = $pdo->prepare('SELECT * FROM grades ORDER BY :sort :order LIMIT :limit OFFSET :offset');
    $stmt->bindParam(':sort', $sort);
    $stmt->bindParam(':order', $order);
    $stmt->bindParam(':limit', $limit);
    $stmt->bindParam(':offset', $offset);

    // Execute query
    $stmt->execute();
    $grades = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return response
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($grades);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input
    $name = isset($input['name']) ? trim($input['name']) : '';
    $grade = isset($input['grade']) ? intval($input['grade']) : 0;

    // Check if user is admin
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('INSERT INTO grades (name, grade) VALUES (:name, :grade)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':grade', $grade);

    // Execute query
    if ($stmt->execute()) {
        // Return response
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Grade created successfully'));
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Validate and sanitize input
    $id = isset($input['id']) ? intval($input['id']) : 0;
    $name = isset($input['name']) ? trim($input['name']) : '';
    $grade = isset($input['grade']) ? intval($input['grade']) : 0;

    // Check if user is admin
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('UPDATE grades SET name = :name, grade = :grade WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':grade', $grade);

    // Execute query
    if ($stmt->execute()) {
        // Return response
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Grade updated successfully'));
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Validate and sanitize input
    $id = isset($input['id']) ? intval($input['id']) : 0;

    // Check if user is admin
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('DELETE FROM grades WHERE id = :id');
    $stmt->bindParam(':id', $id);

    // Execute query
    if ($stmt->execute()) {
        // Return response
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Grade deleted successfully'));
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
} else {
    http_response_code(405);
    echo json_encode(array('error' => 'Method Not Allowed'));
}