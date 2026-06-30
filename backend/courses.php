<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get user role
$user_role = $_SESSION['user_role'];

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Validate and sanitize input
    $course_id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    // Check if user is admin to allow edit and delete
    if ($user_role !== 'admin' && ($course_id || $_GET['all'])) {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    try {
        // Prepare SQL query
        $stmt = $pdo->prepare('SELECT * FROM courses' . ($course_id ? ' WHERE id = :id' : ''));
        $stmt->bindParam(':id', $course_id);
        $stmt->execute();

        // Fetch and return data
        $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($courses);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Read JSON input
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    if (!isset($input['name']) || !isset($input['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Bad Request'));
        exit;
    }

    try {
        // Prepare SQL query
        $stmt = $pdo->prepare('INSERT INTO courses (name, description) VALUES (:name, :description)');
        $stmt->bindParam(':name', $input['name']);
        $stmt->bindParam(':description', $input['description']);
        $stmt->execute();

        // Return created course ID
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(array('id' => $pdo->lastInsertId()));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}

// Handle PUT request
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Read JSON input
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    if (!isset($input['id']) || !isset($input['name']) || !isset($input['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Bad Request'));
        exit;
    }

    // Check if user is admin to allow edit
    if ($user_role !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    try {
        // Prepare SQL query
        $stmt = $pdo->prepare('UPDATE courses SET name = :name, description = :description WHERE id = :id');
        $stmt->bindParam(':id', $input['id']);
        $stmt->bindParam(':name', $input['name']);
        $stmt->bindParam(':description', $input['description']);
        $stmt->execute();

        // Return updated course ID
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('id' => $input['id']));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}

// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Validate and sanitize input
    $course_id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    // Check if user is admin to allow delete
    if ($user_role !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    try {
        // Prepare SQL query
        $stmt = $pdo->prepare('DELETE FROM courses WHERE id = :id');
        $stmt->bindParam(':id', $course_id);
        $stmt->execute();

        // Return deleted course ID
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('id' => $course_id));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}