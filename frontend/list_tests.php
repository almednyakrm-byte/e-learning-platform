<?php
// Session validation
session_start();
if (!isset($_SESSION['authenticated'])) {
    header('Location: login.php');
    exit;
}

// Current user info
$current_user = $_SESSION['username'];

// Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit;
}
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
            <span class="text-lg font-bold">Current User: <?php echo $current_user; ?></span>
            <a href="?logout" class="text-lg font-bold">Logout</a>
        </nav>
    </header>
    <main class="p-4">
        <h1 class="text-3xl font-bold mb-4">Tests List</h1>
        <div class="flex justify-between mb-4">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                <a href="create_tests.php" class="text-white">Add New Item</a>
            </button>
            <input type="text" id="search" class="bg-gray-200 text-gray-700 py-2 px-4 rounded" placeholder="Search...">
        </div>
        <table id="tests-table" class="w-full table-auto border-collapse border border-gray-200">
            <thead class="bg-gray-200">
                <tr>
                    <th class="border border-gray-200 p-2">ID</th>
                    <th class="border border-gray-200 p-2">Name</th>
                    <th class="border border-gray-200 p-2">Actions</th>
                </tr>
            </thead>
            <tbody id="tests-tbody">
                <!-- Table content will be populated by JavaScript -->
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
                        <td class="border border-gray-200 p-2">${test.id}</td>
                        <td class="border border-gray-200 p-2">${test.name}</td>
                        <td class="border border-gray-200 p-2">
                            <a href="edit_tests.php?id=${test.id}" class="text-blue-500 hover:text-blue-700">Edit</a>
                            <button class="text-red-500 hover:text-red-700" onclick="deleteTest(${test.id})">Delete</button>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            });

        // Delete test using Fetch API
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
                    // Remove the deleted row from the table
                    const rows = document.getElementById('tests-tbody').children;
                    for (let i = 0; i < rows.length; i++) {
                        if (rows[i].children[0].textContent == id) {
                            rows[i].remove();
                            break;
                        }
                    }
                }
            });
        }

        // Search bar filtering
        const searchInput = document.getElementById('search');
        searchInput.addEventListener('input', () => {
            const filter = searchInput.value.toLowerCase();
            const rows = document.getElementById('tests-tbody').children;
            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const name = row.children[1].textContent.toLowerCase();
                if (name.includes(filter)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    </script>
</body>
</html>