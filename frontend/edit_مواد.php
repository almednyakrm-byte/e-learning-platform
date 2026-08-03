<?php
// edit_مواد.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: list_مواد.php');
    exit;
}

$id = $_GET['id'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل مواد</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-md mx-auto mt-10 bg-slate-900 p-8 rounded-xl shadow-md">
        <h2 class="text-3xl text-indigo-500 font-bold mb-4">تعديل مواد</h2>
        <form id="edit-form">
            <div class="mb-4">
                <label for="name" class="block text-indigo-500 text-sm font-bold mb-2">اسم المادة</label>
                <input type="text" id="name" name="name" class="bg-slate-900 text-indigo-500 border border-indigo-500 p-2 rounded">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-indigo-500 text-sm font-bold mb-2">وصف المادة</label>
                <textarea id="description" name="description" class="bg-slate-900 text-indigo-500 border border-indigo-500 p-2 rounded"></textarea>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-slate-900 font-bold py-2 px-4 rounded">تعديل</button>
        </form>
    </div>

    <script>
        const id = <?php echo $id; ?>;
        const form = document.getElementById('edit-form');

        // Fetch existing record details
        fetch(`../backend/مواد.php?id=${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('name').value = data.name;
                document.getElementById('description').value = data.description;
            });

        // Submit form using AJAX
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            fetch('../backend/مواد.php', {
                method: 'PUT',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'list_مواد.php';
                } else {
                    console.error(data.error);
                }
            })
            .catch(error => console.error(error));
        });
    </script>
</body>
</html>