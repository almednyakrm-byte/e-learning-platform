**edit_اساتذة.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Fetch existing record details
$existingRecord = json_decode(file_get_contents('../backend/اساتذة.php?id=' . $id), true);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Teacher</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-md shadow-md">
        <h2 class="text-lg font-bold text-slate-900 mb-4">Edit Teacher</h2>
        <form id="edit-teacher-form">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-slate-700">Name</label>
                <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" value="<?= $existingRecord['name'] ?>">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
                <input type="email" id="email" name="email" class="block w-full p-2 mt-1 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" value="<?= $existingRecord['email'] ?>">
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-sm font-medium text-slate-700">Phone</label>
                <input type="tel" id="phone" name="phone" class="block w-full p-2 mt-1 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" value="<?= $existingRecord['phone'] ?>">
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Update</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-teacher-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/اساتذة.php',
                    data: formData,
                    success: function(response) {
                        window.location.href = 'list_<?= $_SESSION['mod_slug'] ?>.php';
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/اساتذة.php**

<?php
// Check if id is set
if (!isset($_GET['id'])) {
    http_response_code(404);
    exit;
}

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get id from URL
$id = $_GET['id'];

// Query to fetch existing record details
$query = "SELECT * FROM teachers WHERE id = '$id'";
$result = $conn->query($query);

// Check if record exists
if ($result->num_rows > 0) {
    // Fetch record details
    $row = $result->fetch_assoc();
    echo json_encode($row);
} else {
    http_response_code(404);
    exit;
}

// Close connection
$conn->close();
?>


**Note:** This code assumes you have a `teachers` table in your database with columns `id`, `name`, `email`, and `phone`. You should replace the placeholders with your actual database credentials and table name. Additionally, this code does not include any validation or sanitization of user input, which is a security risk. You should add proper validation and sanitization to prevent SQL injection and other security vulnerabilities.