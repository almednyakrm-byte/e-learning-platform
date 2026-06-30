**create_grades.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

require_once '../backend/config.php';

// Check if form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form data
    $errors = [];

    $grade_name = trim($_POST['grade_name']);
    if (empty($grade_name)) {
        $errors[] = 'Grade name is required';
    }

    $grade_description = trim($_POST['grade_description']);
    if (empty($grade_description)) {
        $errors[] = 'Grade description is required';
    }

    if (empty($errors)) {
        // Insert new grade record
        $sql = "INSERT INTO grades (grade_name, grade_description) VALUES (?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$grade_name, $grade_description]);

        // Redirect back to list page
        header('Location: list_grades.php');
        exit;
    }
}

// Display form
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Grade</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .slate-900 { color: #1a1d23; }
        .indigo-500 { color: #6b5f7e; }
    </style>
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded shadow-md">
        <h1 class="text-2xl font-bold mb-4">Create Grade</h1>
        <form id="create-grade-form" method="post">
            <div class="mb-4">
                <label for="grade_name" class="block text-gray-700 text-sm font-bold mb-2">Grade Name:</label>
                <input type="text" id="grade_name" name="grade_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Enter grade name">
            </div>
            <div class="mb-4">
                <label for="grade_description" class="block text-gray-700 text-sm font-bold mb-2">Grade Description:</label>
                <textarea id="grade_description" name="grade_description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Enter grade description"></textarea>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Create Grade</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#create-grade-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '../backend/grades.php',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response === 'success') {
                            window.location.href = 'list_grades.php';
                        } else {
                            alert('Error creating grade');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>

**grades.php (backend)**

<?php
require_once '../backend/config.php';

// Check if form data has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form data
    $grade_name = trim($_POST['grade_name']);
    $grade_description = trim($_POST['grade_description']);

    // Insert new grade record
    $sql = "INSERT INTO grades (grade_name, grade_description) VALUES (:grade_name, :grade_description)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':grade_name', $grade_name);
    $stmt->bindParam(':grade_description', $grade_description);
    $stmt->execute();

    // Return success message
    echo 'success';
    exit;
}
?>