**edit_students.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get student ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$student = json_decode(file_get_contents('../backend/students.php?id=' . $id), true);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
        <h2 class="text-lg font-bold text-slate-900 mb-4">Edit Student</h2>
        <form id="edit-student-form">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-slate-900">Name:</label>
                <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-sm text-gray-900 border border-gray-300 rounded-lg" value="<?= $student['name'] ?>">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-slate-900">Email:</label>
                <input type="email" id="email" name="email" class="block w-full p-2 mt-1 text-sm text-gray-900 border border-gray-300 rounded-lg" value="<?= $student['email'] ?>">
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-sm font-medium text-slate-900">Phone:</label>
                <input type="tel" id="phone" name="phone" class="block w-full p-2 mt-1 text-sm text-gray-900 border border-gray-300 rounded-lg" value="<?= $student['phone'] ?>">
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Update Student</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-student-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/students.php',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'list_students.php';
                        } else {
                            alert('Error updating student');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**students.php (backend)**

<?php
// Check if student ID is set
if (isset($_GET['id'])) {
    // Connect to database
    $conn = new mysqli('localhost', 'username', 'password', 'database');

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get student details
    $id = $_GET['id'];
    $sql = "SELECT * FROM students WHERE id = '$id'";
    $result = $conn->query($sql);

    // Check if student exists
    if ($result->num_rows > 0) {
        // Fetch student details
        $student = $result->fetch_assoc();
        echo json_encode($student);
    } else {
        echo json_encode(array('error' => 'Student not found'));
    }

    // Close connection
    $conn->close();
} else {
    echo json_encode(array('error' => 'Student ID not set'));
}
?>


**students.php (backend) - Update student**

<?php
// Check if student ID is set
if (isset($_GET['id'])) {
    // Connect to database
    $conn = new mysqli('localhost', 'username', 'password', 'database');

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get student details
    $id = $_GET['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Update student details
    $sql = "UPDATE students SET name = '$name', email = '$email', phone = '$phone' WHERE id = '$id'";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(array('success' => true));
    } else {
        echo json_encode(array('error' => 'Error updating student'));
    }

    // Close connection
    $conn->close();
} else {
    echo json_encode(array('error' => 'Student ID not set'));
}
?>