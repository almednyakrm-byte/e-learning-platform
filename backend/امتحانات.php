<?php

require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Define routes
$routes = array(
    '/amthanaat' => array('GET', 'GET ALL'),
    '/amthanaat' => array('POST', 'CREATE'),
    '/amthanaat/:id' => array('GET', 'READ'),
    '/amthanaat/:id' => array('PUT', 'UPDATE'),
    '/amthanaat/:id' => array('DELETE', 'DELETE')
);

// Get route parameters
$parts = explode('/', $_SERVER['REQUEST_URI']);
$method = $_SERVER['REQUEST_METHOD'];
$uri = implode('/', array_slice($parts, 1));
$parts = explode('/', $uri);
$id = end($parts);

// Check if user is admin
if ($_SESSION['user_role'] != 'admin') {
    if (in_array($method, array('PUT', 'DELETE'))) {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
}

// Validate input data
if ($method == 'POST') {
    if (!isset($input['name']) || !isset($input['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }
}

// Process request
if ($method == 'GET') {
    if ($id) {
        // READ
        $stmt = $pdo->prepare("SELECT * FROM amthanaat WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch();
        if ($result) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($result);
        } else {
            http_response_code(404);
            echo json_encode(array('error' => 'Not found'));
        }
    } else {
        // GET ALL
        $stmt = $pdo->prepare("SELECT * FROM amthanaat");
        $stmt->execute();
        $results = $stmt->fetchAll();
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($results);
    }
} elseif ($method == 'POST') {
    // CREATE
    $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($input['description'], FILTER_SANITIZE_STRING);
    $stmt = $pdo->prepare("INSERT INTO amthanaat (name, description) VALUES (:name, :description)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(array('message' => 'Created successfully'));
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
} elseif ($method == 'PUT') {
    // UPDATE
    $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($input['description'], FILTER_SANITIZE_STRING);
    $stmt = $pdo->prepare("UPDATE amthanaat SET name = :name, description = :description WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(array('message' => 'Updated successfully'));
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
} elseif ($method == 'DELETE') {
    // DELETE
    $stmt = $pdo->prepare("DELETE FROM amthanaat WHERE id = :id");
    $stmt->bindParam(':id', $id);
    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(array('message' => 'Deleted successfully'));
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}