**create_students.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include header
include 'header.php';

// Include navigation
include 'navigation.php';

// Include form
include 'create_students_form.php';

// Include footer
include 'footer.php';


**create_students_form.php**

<div class="max-w-md mx-auto p-8 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-slate-900 mb-4">Create Student</h2>
    <form id="create-student-form">
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-slate-900">Name</label>
            <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-sm text-gray-900 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-slate-900">Email</label>
            <input type="email" id="email" name="email" class="block w-full p-2 mt-1 text-sm text-gray-900 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>
        <div class="mb-4">
            <label for="phone" class="block text-sm font-medium text-slate-900">Phone</label>
            <input type="tel" id="phone" name="phone" class="block w-full p-2 mt-1 text-sm text-gray-900 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>
        <div class="mb-4">
            <label for="address" class="block text-sm font-medium text-slate-900">Address</label>
            <textarea id="address" name="address" class="block w-full p-2 mt-1 text-sm text-gray-900 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required></textarea>
        </div>
        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Create Student</button>
    </form>
</div>

<script>
    $(document).ready(function() {
        $('#create-student-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/students.php',
                data: formData,
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_students.php';
                    } else {
                        alert('Error creating student');
                    }
                }
            });
        });
    });
</script>


**header.php**

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Student</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body>
    <?php include 'create_students.php'; ?>
</body>
</html>


**navigation.php**

<nav class="bg-slate-900 py-4">
    <div class="container mx-auto px-4 flex justify-between items-center">
        <a href="#" class="text-lg font-bold text-white">Students</a>
        <ul class="flex items-center space-x-4">
            <li><a href="#" class="text-gray-300 hover:text-white">Home</a></li>
            <li><a href="#" class="text-gray-300 hover:text-white">About</a></li>
            <li><a href="#" class="text-gray-300 hover:text-white">Contact</a></li>
        </ul>
    </div>
</nav>


**footer.php**

<footer class="bg-slate-900 py-4">
    <div class="container mx-auto px-4 text-center">
        &copy; 2023 Students Management System
    </div>
</footer>