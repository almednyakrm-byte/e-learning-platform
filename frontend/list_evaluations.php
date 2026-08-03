<?php
// Session validation
session_start();
if (!isset($_SESSION['authenticated'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evaluations List</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <header class="bg-blue-500 text-white p-4">
        <nav class="flex justify-between">
            <a href="index.php" class="text-lg font-bold">Back to Index</a>
            <span class="text-lg font-bold">Welcome, <?php echo $_SESSION['username']; ?></span>
            <a href="logout.php" class="text-lg font-bold">Logout</a>
        </nav>
    </header>
    <main class="p-4">
        <h1 class="text-2xl font-bold mb-4">Evaluations List</h1>
        <div class="flex justify-between mb-4">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                <a href="create_evaluations.php">Add New Item</a>
            </button>
            <input type="search" id="search" placeholder="Search" class="py-2 pl-10 text-sm text-gray-700">
        </div>
        <table id="evaluations-table" class="w-full table-auto border-collapse border border-gray-200">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody id="evaluations-tbody">
                <!-- Table content will be populated by JavaScript -->
            </tbody>
        </table>
    </main>

    <script>
        // Fetch evaluations data from backend
        fetch('../backend/evaluations.php')
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById('evaluations-tbody');
                data.forEach(evaluation => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-4 py-2">${evaluation.id}</td>
                        <td class="px-4 py-2">${evaluation.name}</td>
                        <td class="px-4 py-2">
                            <a href="edit_evaluations.php?id=${evaluation.id}" class="text-blue-500 hover:text-blue-700">Edit</a>
                            <button class="text-red-500 hover:text-red-700" onclick="deleteEvaluation(${evaluation.id})">Delete</button>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            });

        // Delete evaluation by ID
        function deleteEvaluation(id) {
            fetch('../backend/evaluations.php', {
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
                    const rows = document.getElementById('evaluations-tbody').children;
                    for (let i = 0; i < rows.length; i++) {
                        if (rows[i].children[0].textContent == id) {
                            rows[i].remove();
                            break;
                        }
                    }
                } else {
                    console.error('Error deleting evaluation:', data.error);
                }
            });
        }

        // Search functionality
        const searchInput = document.getElementById('search');
        searchInput.addEventListener('input', () => {
            const searchValue = searchInput.value.toLowerCase();
            const rows = document.getElementById('evaluations-tbody').children;
            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const rowText = row.textContent.toLowerCase();
                if (rowText.includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    </script>
</body>
</html>