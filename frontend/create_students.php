<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Include the database connection file
include '../backend/db.php';

// Get the module slug
$mod_slug = 'students';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Student</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-md mx-auto mt-10 p-4 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-blue-500 mb-4">Create Student</h2>
        <form id="create-student-form">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" id="name" name="name" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                <input type="text" id="phone" name="phone" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div class="mb-4">
                <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                <textarea id="address" name="address" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500" required></textarea>
            </div>
            <div class="mb-4">
                <label for="date_of_birth" class="block text-sm font-medium text-gray-700">Date of Birth</label>
                <input type="date" id="date_of_birth" name="date_of_birth" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <button type="submit" class="w-full py-2 px-4 bg-orange-300 text-white rounded-lg hover:bg-orange-400 focus:outline-none focus:ring-orange-300 focus:border-orange-300">Create Student</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#create-student-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '../backend/students.php',
                    data: $(this).serialize(),
                    success: function(data) {
                        window.location.href = 'list_<?php echo $mod_slug; ?>.php';
                    }
                });
            });
        });
    </script>
</body>
</html>