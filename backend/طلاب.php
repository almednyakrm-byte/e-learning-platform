<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Define routes
$routes = array(
    '/students' => array(
        'GET' => function() {
            // Select all students
            $stmt = $pdo->prepare('SELECT * FROM طلاب');
            $stmt->execute();
            $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($students);
        },
        'POST' => function() {
            // Validate input data
            if (!isset($input['name']) || !isset($input['email']) || !isset($input['phone'])) {
                http_response_code(400);
                echo json_encode(array('error' => 'Invalid input'));
                exit;
            }

            // Sanitize input data
            $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
            $email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
            $phone = filter_var($input['phone'], FILTER_SANITIZE_NUMBER_INT);

            // Insert new student
            $stmt = $pdo->prepare('INSERT INTO طلاب (name, email, phone) VALUES (:name, :email, :phone)');
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':phone', $phone);
            $stmt->execute();

            http_response_code(201);
            header('Content-Type: application/json');
            echo json_encode(array('message' => 'Student created successfully'));
        }
    ),
    '/students/{id}' => array(
        'GET' => function($id) {
            // Validate input data
            if (!is_numeric($id)) {
                http_response_code(400);
                echo json_encode(array('error' => 'Invalid input'));
                exit;
            }

            // Select student by ID
            $stmt = $pdo->prepare('SELECT * FROM طلاب WHERE id = :id');
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $student = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$student) {
                http_response_code(404);
                echo json_encode(array('error' => 'Student not found'));
                exit;
            }

            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($student);
        },
        'PUT' => function($id) {
            // Validate input data
            if (!is_numeric($id)) {
                http_response_code(400);
                echo json_encode(array('error' => 'Invalid input'));
                exit;
            }

            // Check if user is admin
            if ($_SESSION['role'] !== 'admin') {
                http_response_code(403);
                echo json_encode(array('error' => 'Forbidden'));
                exit;
            }

            // Validate input data
            if (!isset($input['name']) || !isset($input['email']) || !isset($input['phone'])) {
                http_response_code(400);
                echo json_encode(array('error' => 'Invalid input'));
                exit;
            }

            // Sanitize input data
            $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
            $email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
            $phone = filter_var($input['phone'], FILTER_SANITIZE_NUMBER_INT);

            // Update student
            $stmt = $pdo->prepare('UPDATE طلاب SET name = :name, email = :email, phone = :phone WHERE id = :id');
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':phone', $phone);
            $stmt->execute();

            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(array('message' => 'Student updated successfully'));
        },
        'DELETE' => function($id) {
            // Validate input data
            if (!is_numeric($id)) {
                http_response_code(400);
                echo json_encode(array('error' => 'Invalid input'));
                exit;
            }

            // Check if user is admin
            if ($_SESSION['role'] !== 'admin') {
                http_response_code(403);
                echo json_encode(array('error' => 'Forbidden'));
                exit;
            }

            // Delete student
            $stmt = $pdo->prepare('DELETE FROM طلاب WHERE id = :id');
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(array('message' => 'Student deleted successfully'));
        }
    )
);

// Get route
$route = explode('/', $_SERVER['REQUEST_URI']);
array_shift($route);
array_shift($route);

// Check if route is valid
if (!isset($routes['/' . implode('/', $route)]) || !isset($routes['/' . implode('/', $route)]['' . $_SERVER['REQUEST_METHOD']])) {
    http_response_code(404);
    echo json_encode(array('error' => 'Not found'));
    exit;
}

// Call route handler
$routes['/' . implode('/', $route)]['' . $_SERVER['REQUEST_METHOD']]();