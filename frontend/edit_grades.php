<?php
// edit_grades.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: list_grades.php');
    exit;
}

$id = $_GET['id'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Grades</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-md mx-auto mt-10 p-4 bg-slate-900 text-indigo-500 rounded">
        <h2 class="text-2xl font-bold mb-4">Edit Grades</h2>
        <form id="edit-grades-form">
            <div class="mb-4">
                <label for="grade" class="block text-sm font-medium">Grade</label>
                <input type="text" id="grade" name="grade" class="block w-full p-2 pl-10 text-sm text-gray-200 bg-slate-900 border border-indigo-500 rounded">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium">Description</label>
                <textarea id="description" name="description" class="block w-full p-2 pl-10 text-sm text-gray-200 bg-slate-900 border border-indigo-500 rounded"></textarea>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Update Grades</button>
        </form>
    </div>

    <script>
        const form = document.getElementById('edit-grades-form');
        const id = <?php echo $id; ?>;

        // Fetch existing record details
        fetch(`../backend/grades.php?id=${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('grade').value = data.grade;
                document.getElementById('description').value = data.description;
            });

        // Submit form with AJAX PUT request
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            fetch('../backend/grades.php', {
                method: 'PUT',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'list_grades.php';
                } else {
                    console.error(data.message);
                }
            })
            .catch(error => console.error(error));
        });
    </script>
</body>
</html>