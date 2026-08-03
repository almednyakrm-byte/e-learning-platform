<?php
// edit_instructors.php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: list_instructors.php');
    exit;
}

$id = $_GET['id'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Instructor</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-md mx-auto mt-10 p-4 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-blue-500 mb-4">Edit Instructor</h2>
        <form id="edit-instructor-form">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" id="name" name="name" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-lg focus:outline-none focus:ring-orange-300 focus:border-orange-300">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-lg focus:outline-none focus:ring-orange-300 focus:border-orange-300">
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                <input type="text" id="phone" name="phone" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-lg focus:outline-none focus:ring-orange-300 focus:border-orange-300">
            </div>
            <button type="submit" class="w-full py-2 px-4 bg-blue-500 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-blue-500 focus:border-blue-500">Update Instructor</button>
        </form>
    </div>

    <script>
        // Fetch existing record details
        fetch('../backend/instructors.php?id=<?= $id ?>')
            .then(response => response.json())
            .then(data => {
                document.getElementById('name').value = data.name;
                document.getElementById('email').value = data.email;
                document.getElementById('phone').value = data.phone;
            });

        // Submit form with AJAX PUT request
        document.getElementById('edit-instructor-form').addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            fetch('../backend/instructors.php', {
                method: 'PUT',
                body: JSON.stringify({
                    id: <?= $id ?>,
                    name: formData.get('name'),
                    email: formData.get('email'),
                    phone: formData.get('phone')
                }),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'list_instructors.php';
                } else {
                    console.error(data.error);
                }
            })
            .catch(error => console.error(error));
        });
    </script>
</body>
</html>