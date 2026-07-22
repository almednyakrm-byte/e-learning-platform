<?php
// Session validation
session_start();
if (!isset($_SESSION['authenticated'])) {
    header('Location: login.php');
    exit;
}

// Current user info
$current_user = $_SESSION['username'];

// HTML content
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tests List</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <header class="bg-blue-500 text-white p-4">
        <nav class="flex justify-between">
            <a href="index.php" class="text-lg font-bold">Back to Index</a>
            <span class="text-lg font-bold">Current User: <?= $current_user ?></span>
            <a href="logout.php" class="text-lg font-bold">Logout</a>
        </nav>
    </header>
    <main class="p-4">
        <h1 class="text-3xl font-bold mb-4">Tests List</h1>
        <button class="bg-orange-300 hover:bg-orange-400 text-white font-bold py-2 px-4 rounded mb-4">
            <a href="create_tests.php">Add New Item</a>
        </button>
        <input type="text" id="search" class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-orange-300" placeholder="Search...">
        <table id="tests-table" class="w-full text-left">
            <thead class="bg-blue-500 text-white">
                <tr>
                    <th class="py-2">ID</th>
                    <th class="py-2">Name</th>
                    <th class="py-2">Actions</th>
                </tr>
            </thead>
            <tbody id="tests-tbody">
                <!-- Table content will be populated via AJAX -->
            </tbody>
        </table>
    </main>

    <script>
        // Fetch API to get tests list
        fetch('../backend/tests.php')
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById('tests-tbody');
                data.forEach(test => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${test.id}</td>
                        <td>${test.name}</td>
                        <td>
                            <a href="edit_tests.php?id=${test.id}" class="text-blue-500 hover:text-blue-700">Edit</a>
                            <button class="text-red-500 hover:text-red-700" onclick="deleteTest(${test.id})">Delete</button>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            });

        // Delete test via AJAX
        function deleteTest(id) {
            fetch('../backend/tests.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const row = document.querySelector(`#tests-tbody tr:nth-child(${id + 1})`);
                    row.remove();
                } else {
                    console.error('Error deleting test:', data.error);
                }
            });
        }

        // Search bar filtering
        const searchInput = document.getElementById('search');
        searchInput.addEventListener('input', () => {
            const filter = searchInput.value.toLowerCase();
            const rows = document.querySelectorAll('#tests-tbody tr');
            rows.forEach(row => {
                const name = row.cells[1].textContent.toLowerCase();
                if (name.includes(filter)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>