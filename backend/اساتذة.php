<?php

// Import database connection settings
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized access'));
    exit;
}

// Check if user is admin
if (isset($_SESSION['role']) && $_SESSION['role'] != 'admin') {
    http_response_code(403);
    echo json_encode(array('error' => 'Forbidden access'));
    exit;
}

// Get input data from JSON request body
$inputData = json_decode(file_get_contents('php://input'), true);

// Handle GET request to retrieve all teachers
if (isset($_GET['action']) && $_GET['action'] == 'get_all') {
    try {
        // Prepare SQL query to retrieve all teachers
        $stmt = $pdo->prepare('SELECT * FROM teachers');
        $stmt->execute();
        $teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Return HTTP response with JSON data
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($teachers);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}

// Handle GET request to retrieve a single teacher
if (isset($_GET['action']) && $_GET['action'] == 'get_one') {
    try {
        // Validate teacher ID
        if (!isset($inputData['id']) || !is_numeric($inputData['id'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid teacher ID'));
            exit;
        }
        
        // Prepare SQL query to retrieve a single teacher
        $stmt = $pdo->prepare('SELECT * FROM teachers WHERE id = :id');
        $stmt->bindParam(':id', $inputData['id']);
        $stmt->execute();
        $teacher = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Return HTTP response with JSON data
        if ($teacher) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($teacher);
        } else {
            http_response_code(404);
            echo json_encode(array('error' => 'Teacher not found'));
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}

// Handle POST request to create a new teacher
if (isset($_GET['action']) && $_GET['action'] == 'create') {
    try {
        // Validate input data
        if (!isset($inputData['name']) || !isset($inputData['email']) || !isset($inputData['phone'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid input data'));
            exit;
        }
        
        // Sanitize input data
        $name = htmlspecialchars($inputData['name']);
        $email = htmlspecialchars($inputData['email']);
        $phone = htmlspecialchars($inputData['phone']);
        
        // Prepare SQL query to create a new teacher
        $stmt = $pdo->prepare('INSERT INTO teachers (name, email, phone) VALUES (:name, :email, :phone)');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->execute();
        
        // Return HTTP response with JSON data
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Teacher created successfully'));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}

// Handle PUT request to update an existing teacher
if (isset($_GET['action']) && $_GET['action'] == 'update') {
    try {
        // Validate teacher ID
        if (!isset($inputData['id']) || !is_numeric($inputData['id'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid teacher ID'));
            exit;
        }
        
        // Validate input data
        if (!isset($inputData['name']) || !isset($inputData['email']) || !isset($inputData['phone'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid input data'));
            exit;
        }
        
        // Sanitize input data
        $name = htmlspecialchars($inputData['name']);
        $email = htmlspecialchars($inputData['email']);
        $phone = htmlspecialchars($inputData['phone']);
        
        // Prepare SQL query to update an existing teacher
        $stmt = $pdo->prepare('UPDATE teachers SET name = :name, email = :email, phone = :phone WHERE id = :id');
        $stmt->bindParam(':id', $inputData['id']);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->execute();
        
        // Return HTTP response with JSON data
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Teacher updated successfully'));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}

// Handle DELETE request to delete a teacher
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    try {
        // Validate teacher ID
        if (!isset($inputData['id']) || !is_numeric($inputData['id'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid teacher ID'));
            exit;
        }
        
        // Prepare SQL query to delete a teacher
        $stmt = $pdo->prepare('DELETE FROM teachers WHERE id = :id');
        $stmt->bindParam(':id', $inputData['id']);
        $stmt->execute();
        
        // Return HTTP response with JSON data
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Teacher deleted successfully'));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}

?>