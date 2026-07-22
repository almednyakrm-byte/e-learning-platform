<?php
// Start session
session_start();

// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
include '../backend/db.php';

// Get module slug
$mod_slug = 'students';

// Page title
$page_title = 'Create Student';

// Include header
include 'header.php';
?>

<!-- Create Student Form -->
<div class="max-w-5xl mx-auto p-4 sm:p-6 md:p-8 bg-white rounded-xl shadow-md">
    <h2 class="text-2xl font-bold text-blue-500 mb-4">Create Student</h2>
    <form id="create-student-form">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                <input type="text" id="first_name" name="first_name" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>
            <div>
                <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                <input type="text" id="last_name" name="last_name" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                <input type="text" id="phone" name="phone" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="date_of_birth" class="block text-sm font-medium text-gray-700">Date of Birth</label>
                <input type="date" id="date_of_birth" name="date_of_birth" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>
            <div>
                <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                <select id="gender" name="gender" class="mt-1 block w-full py-2 pl-3 pr-10 text-base text-gray-700 border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                    <option value="">Select</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
            </div>
        </div>
        <button type="submit" class="py-2 px-4 bg-orange-300 text-white font-bold rounded-md hover:bg-orange-400">Create Student</button>
    </form>
</div>

<!-- AJAX JavaScript -->
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
                    if (response == 'success') {
                        window.location.href = 'list_<?php echo $mod_slug; ?>.php';
                    } else {
                        alert('Error: ' + response);
                    }
                }
            });
        });
    });
</script>