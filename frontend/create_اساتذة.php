**create_اساتذة.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/db.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    // Check for empty fields
    if (empty($name) || empty($email) || empty($phone) || empty($address)) {
        $error = 'Please fill in all fields.';
    } else {
        // Insert data into database
        $stmt = $conn->prepare("INSERT INTO اساتذة (name, email, phone, address) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $phone, $address);
        $stmt->execute();
        $stmt->close();

        // Redirect back to list page
        header('Location: list_اساتذة.php');
        exit;
    }
}

// Include header and navigation
require_once '../includes/header.php';
?>

<!-- Create new record form -->
<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-slate-900 mb-4">Create New اساتذة</h2>
    <form id="create-form" method="post">
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-slate-900">Name:</label>
            <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-sm text-gray-900 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-slate-900">Email:</label>
            <input type="email" id="email" name="email" class="block w-full p-2 mt-1 text-sm text-gray-900 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>
        <div class="mb-4">
            <label for="phone" class="block text-sm font-medium text-slate-900">Phone:</label>
            <input type="tel" id="phone" name="phone" class="block w-full p-2 mt-1 text-sm text-gray-900 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>
        <div class="mb-4">
            <label for="address" class="block text-sm font-medium text-slate-900">Address:</label>
            <textarea id="address" name="address" class="block w-full p-2 mt-1 text-sm text-gray-900 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required></textarea>
        </div>
        <button type="submit" name="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Create</button>
    </form>
</div>

<!-- Include footer -->
<?php require_once '../includes/footer.php'; ?>


**create_اساتذة.js**
javascript
$(document).ready(function() {
    // Submit form via AJAX
    $('#create-form').submit(function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            type: 'POST',
            url: '../backend/اساتذة.php',
            data: formData,
            success: function(response) {
                if (response === 'success') {
                    window.location.href = 'list_اساتذة.php';
                } else {
                    alert('Error creating new record.');
                }
            }
        });
    });
});


**backend/اساتذة.php**

<?php
// Include database connection
require_once '../config/db.php';

// Check if form data has been submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    // Check for empty fields
    if (empty($name) || empty($email) || empty($phone) || empty($address)) {
        echo 'error';
    } else {
        // Insert data into database
        $stmt = $conn->prepare("INSERT INTO اساتذة (name, email, phone, address) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $phone, $address);
        $stmt->execute();
        $stmt->close();

        // Redirect back to list page
        echo 'success';
    }
}