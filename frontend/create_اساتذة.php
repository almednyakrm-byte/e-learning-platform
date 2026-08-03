<?php
// create_اساتذة.php

// Session validation
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
include '../backend/db.php';

// Set module slug
$mod_slug = 'اساتذة';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create اساتذة</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-md mx-auto p-4 mt-10 bg-white rounded-lg shadow-md">
        <h2 class="text-lg font-bold text-slate-900 mb-4">Create اساتذة</h2>
        <form id="create-form">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-slate-900">Name</label>
                <input type="text" id="name" name="name" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-slate-900">Email</label>
                <input type="email" id="email" name="email" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-sm font-medium text-slate-900">Phone</label>
                <input type="text" id="phone" name="phone" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div class="mb-4">
                <label for="department" class="block text-sm font-medium text-slate-900">Department</label>
                <select id="department" name="department" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Select Department</option>
                    <?php
                    // Fetch departments from database
                    $query = "SELECT * FROM departments";
                    $result = mysqli_query($conn, $query);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="w-full text-white bg-indigo-500 hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-500 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Create</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#create-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '../backend/اساتذة.php',
                    data: $(this).serialize(),
                    success: function(response) {
                        window.location.href = 'list_اساتذة.php';
                    }
                });
            });
        });
    </script>
</body>
</html>