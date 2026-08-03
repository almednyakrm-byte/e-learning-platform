**edit_طلاب.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details
$existingRecord = json_decode(file_get_contents('../backend/طلاب.php?id=' . $id), true);

// Check if record exists
if (empty($existingRecord)) {
    echo 'Record not found';
    exit;
}

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
        <form id="edit-form">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-slate-900">Name:</label>
                <input type="text" id="name" name="name" value="<?= $existingRecord['name'] ?>" class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-slate-900">Email:</label>
                <input type="email" id="email" name="email" value="<?= $existingRecord['email'] ?>" class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500">
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-sm font-medium text-slate-900">Phone:</label>
                <input type="tel" id="phone" name="phone" value="<?= $existingRecord['phone'] ?>" class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500">
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Update Student</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/طلاب.php',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'list_طلاب.php';
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


**backend/طلاب.php**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    echo json_encode(array('error' => 'ID not set'));
    exit;
}

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    echo json_encode(array('error' => 'Connection failed'));
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Query to fetch existing record details
$query = "SELECT * FROM students WHERE id = '$id'";
$result = $conn->query($query);

// Check if record exists
if ($result->num_rows > 0) {
    // Fetch record details
    $row = $result->fetch_assoc();
    echo json_encode($row);
} else {
    echo json_encode(array('error' => 'Record not found'));
}

// Close connection
$conn->close();
?>