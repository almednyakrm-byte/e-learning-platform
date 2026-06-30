**create_courses.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
include 'header.php';
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:px-12">
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6 lg:p-8">
        <h2 class="text-slate-900 font-bold text-lg mb-4">Create New Course</h2>
        <form id="create-course-form" class="space-y-4">
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label for="course_name" class="block uppercase tracking-wide text-slate-900 text-xs font-bold mb-2">Course Name</label>
                    <input type="text" id="course_name" name="course_name" class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" placeholder="Enter course name">
                </div>
                <div class="w-full md:w-1/2 px-3">
                    <label for="course_code" class="block uppercase tracking-wide text-slate-900 text-xs font-bold mb-2">Course Code</label>
                    <input type="text" id="course_code" name="course_code" class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" placeholder="Enter course code">
                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label for="course_description" class="block uppercase tracking-wide text-slate-900 text-xs font-bold mb-2">Course Description</label>
                    <textarea id="course_description" name="course_description" class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" placeholder="Enter course description"></textarea>
                </div>
                <div class="w-full md:w-1/2 px-3">
                    <label for="course_level" class="block uppercase tracking-wide text-slate-900 text-xs font-bold mb-2">Course Level</label>
                    <select id="course_level" name="course_level" class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                        <option value="">Select course level</option>
                        <option value="Beginner">Beginner</option>
                        <option value="Intermediate">Intermediate</option>
                        <option value="Advanced">Advanced</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Create Course</button>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#create-course-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/courses.php',
                data: formData,
                success: function(response) {
                    if (response == 'success') {
                        window.location.href = 'list_courses.php';
                    } else {
                        alert('Error creating course');
                    }
                }
            });
        });
    });
</script>

<?php
// Include footer
include 'footer.php';
?>


**courses.php (backend)**

<?php
// Check if form data is submitted
if (isset($_POST['course_name']) && isset($_POST['course_code']) && isset($_POST['course_description']) && isset($_POST['course_level'])) {
    // Connect to database
    $conn = mysqli_connect('localhost', 'username', 'password', 'database');
    
    // Check connection
    if (!$conn) {
        die('Connection failed: ' . mysqli_connect_error());
    }
    
    // Insert data into courses table
    $course_name = mysqli_real_escape_string($conn, $_POST['course_name']);
    $course_code = mysqli_real_escape_string($conn, $_POST['course_code']);
    $course_description = mysqli_real_escape_string($conn, $_POST['course_description']);
    $course_level = mysqli_real_escape_string($conn, $_POST['course_level']);
    
    $sql = "INSERT INTO courses (course_name, course_code, course_description, course_level) VALUES ('$course_name', '$course_code', '$course_description', '$course_level')";
    
    if (mysqli_query($conn, $sql)) {
        echo 'success';
    } else {
        echo 'Error creating course: ' . mysqli_error($conn);
    }
    
    // Close connection
    mysqli_close($conn);
} else {
    echo 'Error creating course';
}
?>