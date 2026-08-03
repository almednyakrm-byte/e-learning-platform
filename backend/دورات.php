<?php

require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Define allowed roles for each operation
$allowedRoles = [
    'GET' => ['user'],
    'POST' => ['user'],
    'PUT' => ['admin'],
    'DELETE' => ['admin']
];

// Check if user has permission to perform the requested operation
if (isset($input['id']) && in_array($_SESSION['user_role'], $allowedRoles[$_SERVER['REQUEST_METHOD']])) {
    // Process the request
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            // Retrieve all courses
            $stmt = $pdo->prepare('SELECT * FROM دورات');
            $stmt->execute();
            $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($courses);
            break;
        case 'POST':
            // Validate input data
            if (!isset($input['name']) || !isset($input['description'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid request data']);
                exit;
            }
            
            // Sanitize input data
            $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
            $description = filter_var($input['description'], FILTER_SANITIZE_STRING);
            
            // Insert new course
            $stmt = $pdo->prepare('INSERT INTO دورات (name, description) VALUES (:name, :description)');
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':description', $description);
            $stmt->execute();
            http_response_code(201);
            header('Content-Type: application/json');
            echo json_encode(['message' => 'Course created successfully']);
            break;
        case 'PUT':
            // Validate input data
            if (!isset($input['id']) || !isset($input['name']) || !isset($input['description'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid request data']);
                exit;
            }
            
            // Sanitize input data
            $id = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);
            $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
            $description = filter_var($input['description'], FILTER_SANITIZE_STRING);
            
            // Update existing course
            $stmt = $pdo->prepare('UPDATE دورات SET name = :name, description = :description WHERE id = :id');
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':description', $description);
            $stmt->execute();
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(['message' => 'Course updated successfully']);
            break;
        case 'DELETE':
            // Validate input data
            if (!isset($input['id'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid request data']);
                exit;
            }
            
            // Sanitize input data
            $id = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);
            
            // Delete existing course
            $stmt = $pdo->prepare('DELETE FROM دورات WHERE id = :id');
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(['message' => 'Course deleted successfully']);
            break;
        default:
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            break;
    }
} else {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden']);
}



// Example usage:
// GET all courses
curl -X GET http://example.com/dorats.php

// POST new course
curl -X POST -H "Content-Type: application/json" -d '{"name": "Course Name", "description": "Course Description"}' http://example.com/dorats.php

// PUT existing course
curl -X PUT -H "Content-Type: application/json" -d '{"id": 1, "name": "Updated Course Name", "description": "Updated Course Description"}' http://example.com/dorats.php

// DELETE existing course
curl -X DELETE -H "Content-Type: application/json" -d '{"id": 1}' http://example.com/dorats.php