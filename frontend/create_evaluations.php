<?php
// create_evaluations.php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

include_once '../config.php';
$mod_slug = 'evaluations';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Evaluations</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-5xl mx-auto p-4 sm:p-6 md:p-8 bg-white rounded shadow-md">
        <h2 class="text-3xl text-blue-500 font-bold mb-4">Create Evaluations</h2>
        <form id="create-evaluations-form">
            <div class="mb-4">
                <label for="evaluation_name" class="block text-gray-700 text-sm font-bold mb-2">Evaluation Name</label>
                <input type="text" id="evaluation_name" name="evaluation_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Enter evaluation name">
            </div>
            <div class="mb-4">
                <label for="evaluation_description" class="block text-gray-700 text-sm font-bold mb-2">Evaluation Description</label>
                <textarea id="evaluation_description" name="evaluation_description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Enter evaluation description"></textarea>
            </div>
            <div class="mb-4">
                <label for="evaluation_date" class="block text-gray-700 text-sm font-bold mb-2">Evaluation Date</label>
                <input type="date" id="evaluation_date" name="evaluation_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Enter evaluation date">
            </div>
            <div class="mb-4">
                <label for="evaluation_status" class="block text-gray-700 text-sm font-bold mb-2">Evaluation Status</label>
                <select id="evaluation_status" name="evaluation_status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="pending">Pending</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Create Evaluations</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#create-evaluations-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '../backend/evaluations.php',
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