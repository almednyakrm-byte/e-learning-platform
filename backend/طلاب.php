<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Check if user is admin
if (isset($_SESSION['role']) && $_SESSION['role'] != 'admin') {
    http_response_code(403);
    echo json_encode(array('error' => 'Forbidden'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Validate input data
if (!$input) {
    http_response_code(400);
    echo json_encode(array('error' => 'Invalid request'));
    exit;
}

// Define table name
$tableName = 'طلاب';

// Define columns
$columns = array('id', 'name', 'email', 'phone');

// Define validation rules
$validationRules = array(
    'name' => array('required' => true, 'min' => 3, 'max' => 50),
    'email' => array('required' => true, 'email' => true),
    'phone' => array('required' => true, 'min' => 10, 'max' => 15),
);

// Validate input data
foreach ($validationRules as $column => $rules) {
    if (isset($input[$column])) {
        $value = $input[$column];
        if (isset($rules['required']) && empty($value)) {
            http_response_code(400);
            echo json_encode(array('error' => "Field '$column' is required"));
            exit;
        }
        if (isset($rules['min']) && strlen($value) < $rules['min']) {
            http_response_code(400);
            echo json_encode(array('error' => "Field '$column' must be at least " . $rules['min'] . " characters"));
            exit;
        }
        if (isset($rules['max']) && strlen($value) > $rules['max']) {
            http_response_code(400);
            echo json_encode(array('error' => "Field '$column' must not exceed " . $rules['max'] . " characters"));
            exit;
        }
        if (isset($rules['email']) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode(array('error' => "Field '$column' must be a valid email address"));
            exit;
        }
    }
}

// Sanitize input data
foreach ($columns as $column) {
    if (isset($input[$column])) {
        $input[$column] = filter_var($input[$column], FILTER_SANITIZE_STRING);
    }
}

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    try {
        $stmt = $pdo->prepare("SELECT * FROM $tableName");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($data);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}

// Handle POST request
elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $stmt = $pdo->prepare("INSERT INTO $tableName (name, email, phone) VALUES (:name, :email, :phone)");
        $stmt->bindParam(':name', $input['name']);
        $stmt->bindParam(':email', $input['email']);
        $stmt->bindParam(':phone', $input['phone']);
        $stmt->execute();
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Student created successfully'));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}

// Handle PUT request
elseif ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    try {
        $stmt = $pdo->prepare("UPDATE $tableName SET name = :name, email = :email, phone = :phone WHERE id = :id");
        $stmt->bindParam(':id', $input['id']);
        $stmt->bindParam(':name', $input['name']);
        $stmt->bindParam(':email', $input['email']);
        $stmt->bindParam(':phone', $input['phone']);
        $stmt->execute();
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Student updated successfully'));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}

// Handle DELETE request
elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    try {
        $stmt = $pdo->prepare("DELETE FROM $tableName WHERE id = :id");
        $stmt->bindParam(':id', $input['id']);
        $stmt->execute();
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Student deleted successfully'));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}