<?php
// Start session
session_start();

// Session validation
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../backend/db.php';

// Define module slug
$mod_slug = 'courses';

// Define page title
$page_title = 'Create Course';

// Include header
require_once 'header.php';
?>

<main class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <h1 class="text-3xl font-bold text-blue-500"><?= $page_title ?></h1>
        <form id="create-course-form" class="mt-8 space-y-6">
            <div class="rounded-md shadow-sm">
                <div>
                    <label for="course_name" class="block text-sm font-medium text-gray-700">Course Name</label>
                    <input type="text" id="course_name" name="course_name" autocomplete="course_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                </div>
                <div class="mt-4">
                    <label for="course_code" class="block text-sm font-medium text-gray-700">Course Code</label>
                    <input type="text" id="course_code" name="course_code" autocomplete="course_code" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                </div>
                <div class="mt-4">
                    <label for="course_description" class="block text-sm font-medium text-gray-700">Course Description</label>
                    <textarea id="course_description" name="course_description" autocomplete="course_description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"></textarea>
                </div>
                <div class="mt-4">
                    <label for="course_duration" class="block text-sm font-medium text-gray-700">Course Duration</label>
                    <input type="text" id="course_duration" name="course_duration" autocomplete="course_duration" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                </div>
                <div class="mt-4">
                    <label for="course_fees" class="block text-sm font-medium text-gray-700">Course Fees</label>
                    <input type="number" id="course_fees" name="course_fees" autocomplete="course_fees" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                </div>
            </div>
            <div>
                <button type="submit" class="inline-flex w-full justify-center rounded-md border border-transparent bg-blue-500 py-2 px-4 text-base font-medium text-white shadow-sm hover:bg-orange-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm">Create Course</button>
            </div>
        </form>
    </div>
</main>

<script>
    $(document).ready(function() {
        $('#create-course-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/courses.php',
                data: $(this).serialize(),
                success: function(data) {
                    window.location.href = 'list_<?= $mod_slug ?>.php';
                }
            });
        });
    });
</script>

<?php
// Include footer
require_once 'footer.php';
?>