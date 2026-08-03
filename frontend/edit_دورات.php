**edit_دورات.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Get the ID from the URL
$id = $_GET['id'];

// Fetch the existing record details via AJAX
$existingRecord = json_decode(file_get_contents('../backend/دورات.php?id=' . $id), true);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit دورات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
        <h2 class="text-lg font-bold text-slate-900 mb-4">Edit دورات</h2>
        <form id="edit-form" class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-slate-900">Name:</label>
                <input type="text" id="name" name="name" class="block w-full p-2 pl-10 text-sm text-gray-900 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" value="<?= $existingRecord['name'] ?>">
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-slate-900">Description:</label>
                <textarea id="description" name="description" class="block w-full p-2 pl-10 text-sm text-gray-900 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" rows="4"><?= $existingRecord['description'] ?></textarea>
            </div>
            <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-indigo-500 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-300">Update</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/دورات.php',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'list_<?= $_SESSION['mod_slug'] ?>.php';
                        } else {
                            alert('Error updating record');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**../backend/دورات.php**

<?php
// Check if the ID is set
if (!isset($_GET['id'])) {
    die('Invalid ID');
}

// Get the ID
$id = $_GET['id'];

// Fetch the existing record details
$existingRecord = array(
    'id' => $id,
    'name' => 'Existing Record Name', // Replace with actual database query
    'description' => 'Existing Record Description' // Replace with actual database query
);

// Output the existing record details as JSON
echo json_encode($existingRecord);