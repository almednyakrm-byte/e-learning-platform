<?php
// edit_tests.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: list_tests.php');
    exit;
}

$id = $_GET['id'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Test</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-md mx-auto mt-10 p-4 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-blue-500 mb-4">Edit Test</h2>
        <form id="edit-test-form">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-blue-500">Name</label>
                <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-sm text-gray-700 border border-gray-200 rounded-lg focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-blue-500">Description</label>
                <textarea id="description" name="description" class="block w-full p-2 mt-1 text-sm text-gray-700 border border-gray-200 rounded-lg focus:ring-blue-500 focus:border-blue-500"></textarea>
            </div>
            <button type="submit" class="py-2 px-4 bg-orange-300 text-white rounded-lg hover:bg-orange-400 focus:ring-orange-300 focus:ring-offset-orange-200">Update Test</button>
        </form>
    </div>

    <script>
        const form = document.getElementById('edit-test-form');
        const id = <?php echo $id; ?>;

        // Fetch existing record details
        fetch(`../backend/tests.php?id=${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('name').value = data.name;
                document.getElementById('description').value = data.description;
            });

        // Submit form using AJAX
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            fetch('../backend/tests.php', {
                method: 'PUT',
                body: JSON.stringify({
                    id: id,
                    name: formData.get('name'),
                    description: formData.get('description')
                }),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'list_tests.php';
                } else {
                    console.error(data.error);
                }
            })
            .catch(error => console.error(error));
        });
    </script>
</body>
</html>