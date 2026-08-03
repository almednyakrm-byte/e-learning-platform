<?php

// Import database connection settings
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data from JSON body or POST data
$inputData = json_decode(file_get_contents('php://input'), true);
if (!$inputData) {
    $inputData = $_POST;
}

// Define CRUD operations
function getMakarat() {
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM makarat');
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function createMakarat() {
    global $pdo;
    // Validate and sanitize input data
    $validatedData = validateMakarat($inputData);
    if (!$validatedData) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input data'));
        exit;
    }
    $stmt = $pdo->prepare('INSERT INTO makarat (name, description) VALUES (:name, :description)');
    $stmt->execute($validatedData);
    http_response_code(201);
    echo json_encode(array('message' => 'Makarat created successfully'));
}

function updateMakarat($id) {
    global $pdo;
    // Validate and sanitize input data
    $validatedData = validateMakarat($inputData);
    if (!$validatedData) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input data'));
        exit;
    }
    $stmt = $pdo->prepare('UPDATE makarat SET name = :name, description = :description WHERE id = :id');
    $stmt->execute(array_merge($validatedData, array('id' => $id)));
    http_response_code(200);
    echo json_encode(array('message' => 'Makarat updated successfully'));
}

function deleteMakarat($id) {
    global $pdo;
    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    $stmt = $pdo->prepare('DELETE FROM makarat WHERE id = :id');
    $stmt->execute(array('id' => $id));
    http_response_code(200);
    echo json_encode(array('message' => 'Makarat deleted successfully'));
}

// Validate and sanitize input data
function validateMakarat($data) {
    if (!isset($data['name']) || !isset($data['description'])) {
        return false;
    }
    $data['name'] = trim($data['name']);
    $data['description'] = trim($data['description']);
    return $data;
}

// Handle CRUD operations
switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $stmt = $pdo->prepare('SELECT * FROM makarat WHERE id = :id');
            $stmt->execute(array('id' => $id));
            $makarat = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($makarat) {
                http_response_code(200);
                echo json_encode($makarat);
            } else {
                http_response_code(404);
                echo json_encode(array('error' => 'Not found'));
            }
        } else {
            $makarat = getMakarat();
            http_response_code(200);
            echo json_encode($makarat);
        }
        break;
    case 'POST':
        createMakarat();
        break;
    case 'PUT':
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            updateMakarat($id);
        } else {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid request'));
        }
        break;
    case 'DELETE':
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            deleteMakarat($id);
        } else {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid request'));
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(array('error' => 'Method not allowed'));
}

// Set response headers
header('Content-Type: application/json');