<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$inputData = json_decode(file_get_contents('php://input'), true);
if ($inputData === null) {
    $inputData = $_POST;
}

// Validate input data
if (!isset($inputData['id']) && !isset($inputData['name']) && !isset($inputData['description'])) {
    http_response_code(400);
    echo json_encode(array('error' => 'Invalid request'));
    exit;
}

// Sanitize input data
$inputData['name'] = filter_var($inputData['name'], FILTER_SANITIZE_STRING);
$inputData['description'] = filter_var($inputData['description'], FILTER_SANITIZE_STRING);

// Connect to database
$db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// GET all courses
if (isset($_GET['action']) && $_GET['action'] == 'get_all') {
    $stmt = $db->prepare('SELECT * FROM دورات');
    $stmt->execute();
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($courses);
    exit;
}

// GET course by id
if (isset($_GET['action']) && $_GET['action'] == 'get_by_id') {
    $stmt = $db->prepare('SELECT * FROM دورات WHERE id = :id');
    $stmt->bindParam(':id', $inputData['id']);
    $stmt->execute();
    $course = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($course) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($course);
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'Course not found'));
    }
    exit;
}

// POST create course
if (isset($_GET['action']) && $_GET['action'] == 'create') {
    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    $stmt = $db->prepare('INSERT INTO دورات (name, description) VALUES (:name, :description)');
    $stmt->bindParam(':name', $inputData['name']);
    $stmt->bindParam(':description', $inputData['description']);
    $stmt->execute();
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Course created successfully'));
    exit;
}

// PUT update course
if (isset($_GET['action']) && $_GET['action'] == 'update') {
    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    $stmt = $db->prepare('UPDATE دورات SET name = :name, description = :description WHERE id = :id');
    $stmt->bindParam(':id', $inputData['id']);
    $stmt->bindParam(':name', $inputData['name']);
    $stmt->bindParam(':description', $inputData['description']);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Course updated successfully'));
    exit;
}

// DELETE course
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    $stmt = $db->prepare('DELETE FROM دورات WHERE id = :id');
    $stmt->bindParam(':id', $inputData['id']);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Course deleted successfully'));
    exit;
}

http_response_code(404);
echo json_encode(array('error' => 'Not found'));