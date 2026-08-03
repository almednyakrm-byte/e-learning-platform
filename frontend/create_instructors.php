<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Include the database connection file
include '../backend/db.php';

// Define the module slug
$mod_slug = 'instructors';

// Define the page title
$page_title = 'Create Instructor';

// Include the header file
include 'header.php';
?>

<!-- Premium Tailwind UI form -->
<div class="max-w-5xl mx-auto p-4 sm:p-6 md:p-8 bg-white rounded-xl shadow-md">
    <h2 class="text-3xl text-blue-500 font-bold mb-4">Create Instructor</h2>
    <form id="create-instructor-form">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                <input type="text" id="first_name" name="first_name" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div>
                <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                <input type="text" id="last_name" name="last_name" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                <input type="text" id="phone" name="phone" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="department" class="block text-sm font-medium text-gray-700">Department</label>
                <select id="department" name="department" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="">Select Department</option>
                    <option value="Mathematics">Mathematics</option>
                    <option value="Science">Science</option>
                    <option value="English">English</option>
                </select>
            </div>
            <div>
                <label for="designation" class="block text-sm font-medium text-gray-700">Designation</label>
                <input type="text" id="designation" name="designation" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
            </div>
        </div>
        <button type="submit" class="bg-orange-300 hover:bg-orange-400 text-white font-bold py-2 px-4 rounded-md">Create Instructor</button>
    </form>
</div>

<script>
    // AJAX JavaScript to POST the form data to '../backend/instructors.php'
    $(document).ready(function() {
        $('#create-instructor-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/instructors.php',
                data: formData,
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_<?php echo $mod_slug; ?>.php';
                    } else {
                        alert('Error: ' + response);
                    }
                }
            });
        });
    });
</script>

<?php
// Include the footer file
include 'footer.php';
?>