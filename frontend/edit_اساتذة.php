**edit_اساتذة.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via AJAX
$existingRecord = json_decode(file_get_contents('../backend/اساتذة.php?id=' . $id), true);

// Check if record exists
if (empty($existingRecord)) {
    echo 'Error: Record not found.';
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Teacher</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
        <h2 class="text-lg font-bold text-slate-900 mb-4">Edit Teacher</h2>
        <form id="edit-teacher-form">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-slate-900">Name:</label>
                <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-sm text-gray-900 bg-white border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" value="<?= $existingRecord['name'] ?>">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-slate-900">Email:</label>
                <input type="email" id="email" name="email" class="block w-full p-2 mt-1 text-sm text-gray-900 bg-white border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" value="<?= $existingRecord['email'] ?>">
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-sm font-medium text-slate-900">Phone:</label>
                <input type="tel" id="phone" name="phone" class="block w-full p-2 mt-1 text-sm text-gray-900 bg-white border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" value="<?= $existingRecord['phone'] ?>">
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Update Teacher</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                        if (response === 'success') {
                            window.location.href = 'list_<?= $_SESSION['mod_slug'] ?>.php';
                        } else {
                            alert('Error updating teacher.');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/اساتذة.php**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    echo 'Error: ID not set.';
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch existing record details
$query = "SELECT * FROM teachers WHERE id = '$id'";
$result = $conn->query($query);

// Check if record exists
if ($result->num_rows > 0) {
    // Fetch record details
    $row = $result->fetch_assoc();
    echo json_encode($row);
} else {
    echo 'Error: Record not found.';
}

// Close database connection
$conn->close();
?>


**backend/update_teacher.php**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    echo 'Error: ID not set.';
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Get form data
$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to update teacher
$query = "UPDATE teachers SET name = '$name', email = '$email', phone = '$phone' WHERE id = '$id'";
$conn->query($query);

// Check if update was successful
if ($conn->affected_rows > 0) {
    echo 'success';
} else {
    echo 'Error updating teacher.';
}

// Close database connection
$conn->close();
?>