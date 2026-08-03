<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Check if user is admin
if (isset($_POST['action']) && in_array($_POST['action'], array('edit', 'delete'))) {
    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
}

// Get input data
$input_data = json_decode(file_get_contents('php://input'), true);
if (!$input_data) {
    $input_data = $_POST;
}

// Validate input data
if (!isset($input_data['action'])) {
    http_response_code(400);
    echo json_encode(array('error' => 'Invalid request'));
    exit;
}

// Handle CRUD operations
switch ($input_data['action']) {
    case 'get':
        // Get all teachers
        $stmt = $pdo->prepare('SELECT * FROM teachers');
        $stmt->execute();
        $teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($teachers);
        break;
    case 'create':
        // Validate input data
        if (!isset($input_data['name']) || !isset($input_data['email']) || !isset($input_data['phone'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid request'));
            exit;
        }

        // Sanitize input data
        $name = filter_var($input_data['name'], FILTER_SANITIZE_STRING);
        $email = filter_var($input_data['email'], FILTER_SANITIZE_EMAIL);
        $phone = filter_var($input_data['phone'], FILTER_SANITIZE_NUMBER_INT);

        // Insert new teacher
        $stmt = $pdo->prepare('INSERT INTO teachers (name, email, phone) VALUES (:name, :email, :phone)');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->execute();

        http_response_code(201);
        echo json_encode(array('message' => 'Teacher created successfully'));
        break;
    case 'edit':
        // Validate input data
        if (!isset($input_data['id']) || !isset($input_data['name']) || !isset($input_data['email']) || !isset($input_data['phone'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid request'));
            exit;
        }

        // Sanitize input data
        $id = filter_var($input_data['id'], FILTER_SANITIZE_NUMBER_INT);
        $name = filter_var($input_data['name'], FILTER_SANITIZE_STRING);
        $email = filter_var($input_data['email'], FILTER_SANITIZE_EMAIL);
        $phone = filter_var($input_data['phone'], FILTER_SANITIZE_NUMBER_INT);

        // Update existing teacher
        $stmt = $pdo->prepare('UPDATE teachers SET name = :name, email = :email, phone = :phone WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->execute();

        http_response_code(200);
        echo json_encode(array('message' => 'Teacher updated successfully'));
        break;
    case 'delete':
        // Validate input data
        if (!isset($input_data['id'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid request'));
            exit;
        }

        // Sanitize input data
        $id = filter_var($input_data['id'], FILTER_SANITIZE_NUMBER_INT);

        // Delete teacher
        $stmt = $pdo->prepare('DELETE FROM teachers WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        http_response_code(200);
        echo json_encode(array('message' => 'Teacher deleted successfully'));
        break;
    default:
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        break;
}