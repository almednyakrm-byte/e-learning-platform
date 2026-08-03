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
    <div class="container mx-auto p-4 pt-6 mt-10 bg-gray-200 rounded">
        <h2 class="text-2xl text-blue-500">Edit Test</h2>
        <form id="edit-test-form">
            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name:</label>
                <input type="text" id="name" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description:</label>
                <textarea id="description" name="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update Test</button>
        </form>
    </div>

    <script>
        const form = document.getElementById('edit-test-form');
        const id = '<?php echo $id; ?>';

        // Fetch existing record details
        fetch(`../backend/tests.php?id=${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('name').value = data.name;
                document.getElementById('description').value = data.description;
            });

        // Submit form
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            fetch(`../backend/tests.php?id=${id}`, {
                method: 'PUT',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'list_tests.php';
                } else {
                    console.error(data.message);
                }
            })
            .catch(error => console.error(error));
        });
    </script>
</body>
</html>