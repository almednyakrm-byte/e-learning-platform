<?php
// edit_employees.php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: list_employees.php');
    exit;
}

$id = $_GET['id'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Employee</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-md mx-auto mt-10 p-4 bg-gray-200 rounded-lg shadow-md">
        <h2 class="text-2xl text-blue-500 font-bold mb-4">Edit Employee</h2>
        <form id="edit-employee-form">
            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name</label>
                <input type="text" id="name" name="name" class="block w-full p-2 bg-gray-200 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                <input type="email" id="email" name="email" class="block w-full p-2 bg-gray-200 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <label for="position" class="block text-gray-700 text-sm font-bold mb-2">Position</label>
                <input type="text" id="position" name="position" class="block w-full p-2 bg-gray-200 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit" class="w-full p-2 bg-blue-500 text-white font-bold rounded-lg hover:bg-blue-700">Update Employee</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            const id = '<?php echo $id; ?>';
            $.ajax({
                type: 'GET',
                url: '../backend/employees.php?id=' + id,
                dataType: 'json',
                success: function(data) {
                    $('#name').val(data.name);
                    $('#email').val(data.email);
                    $('#position').val(data.position);
                }
            });

            $('#edit-employee-form').submit(function(e) {
                e.preventDefault();
                const formData = {
                    id: id,
                    name: $('#name').val(),
                    email: $('#email').val(),
                    position: $('#position').val()
                };

                $.ajax({
                    type: 'PUT',
                    url: '../backend/employees.php',
                    data: JSON.stringify(formData),
                    contentType: 'application/json',
                    success: function(data) {
                        window.location.href = 'list_employees.php';
                    }
                });
            });
        });
    </script>
</body>
</html>