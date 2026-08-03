**create_امتحانات.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/db.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $date = trim($_POST['date']);
    $time = trim($_POST['time']);

    if (!empty($name) && !empty($description) && !empty($date) && !empty($time)) {
        // Insert data into database
        $query = "INSERT INTO امتحانات (name, description, date, time) VALUES ('$name', '$description', '$date', '$time')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            // Redirect back to list page
            header('Location: list_امتحانات.php');
            exit;
        } else {
            echo 'Error inserting data';
        }
    } else {
        echo 'Please fill in all fields';
    }
}

// Include header
require_once '../includes/header.php';

?>

<!-- Create exam form -->
<div class="max-w-md mx-auto p-8 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-bold text-slate-900 mb-4">Create Exam</h2>
    <form id="create-exam-form" method="POST">
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-slate-900">Exam Name:</label>
            <input type="text" id="name" name="name" class="block w-full p-2 pl-10 text-sm text-slate-900 placeholder-slate-400 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" placeholder="Exam Name">
        </div>
        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-slate-900">Exam Description:</label>
            <textarea id="description" name="description" class="block w-full p-2 pl-10 text-sm text-slate-900 placeholder-slate-400 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" placeholder="Exam Description"></textarea>
        </div>
        <div class="mb-4">
            <label for="date" class="block text-sm font-medium text-slate-900">Exam Date:</label>
            <input type="date" id="date" name="date" class="block w-full p-2 pl-10 text-sm text-slate-900 placeholder-slate-400 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" placeholder="Exam Date">
        </div>
        <div class="mb-4">
            <label for="time" class="block text-sm font-medium text-slate-900">Exam Time:</label>
            <input type="time" id="time" name="time" class="block w-full p-2 pl-10 text-sm text-slate-900 placeholder-slate-400 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" placeholder="Exam Time">
        </div>
        <button type="submit" name="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-indigo-500 border border-indigo-500 rounded-lg hover:bg-indigo-600 focus:ring-indigo-500 focus:border-indigo-500">Create Exam</button>
    </form>
</div>

<!-- Include footer -->
<?php require_once '../includes/footer.php'; ?>

<!-- AJAX script -->
<script>
    $(document).ready(function() {
        $('#create-exam-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/امتحانات.php',
                data: $(this).serialize(),
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_امتحانات.php';
                    } else {
                        alert('Error creating exam');
                    }
                }
            });
        });
    });
</script>

**Note:** This code assumes you have a `db.php` file that connects to your database and a `header.php` and `footer.php` file that includes the necessary HTML for the page header and footer. You will need to modify the code to fit your specific database schema and file structure.