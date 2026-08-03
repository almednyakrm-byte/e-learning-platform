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
    <title>فصول</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-slate-900 text-white">
    <header class="bg-indigo-500 py-4">
        <nav class="container mx-auto flex justify-between">
            <a href="index.php" class="text-lg font-bold">Back to Index</a>
            <span class="text-lg font-bold">Current User: <?php echo $_SESSION['username']; ?></span>
            <a href="logout.php" class="text-lg font-bold">Logout</a>
        </nav>
    </header>
    <main class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-24">
        <h1 class="text-3xl font-bold mb-4">فصول</h1>
        <div class="flex justify-between mb-4">
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                <a href="create_فصول.php">Add New Item</a>
            </button>
            <input type="text" id="search" class="bg-slate-800 text-white font-bold py-2 px-4 rounded" placeholder="Search...">
        </div>
        <table id="records" class="w-full text-white">
            <thead class="bg-slate-800">
                <tr>
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody id="table-body">
                <!-- Records will be populated here -->
            </tbody>
        </table>
    </main>

    <script>
        // Fetch records from backend
        fetch('../backend/فصول.php')
            .then(response => response.json())
            .then(data => {
                const tableBody = document.getElementById('table-body');
                data.forEach(record => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-4 py-2">${record.id}</td>
                        <td class="px-4 py-2">${record.name}</td>
                        <td class="px-4 py-2">
                            <a href="edit_فصول.php?id=${record.id}" class="text-indigo-500 hover:text-indigo-700">Edit</a>
                            <button class="text-red-500 hover:text-red-700" onclick="deleteRecord(${record.id})">Delete</button>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });
            });

        // Delete record
        function deleteRecord(id) {
            fetch('../backend/فصول.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove the deleted record from the table
                        const tableBody = document.getElementById('table-body');
                        const rows = tableBody.children;
                        for (let i = 0; i < rows.length; i++) {
                            const row = rows[i];
                            const idCell = row.children[0];
                            if (idCell.textContent == id) {
                                tableBody.removeChild(row);
                                break;
                            }
                        }
                    }
                });
        }

        // Search functionality
        const searchInput = document.getElementById('search');
        searchInput.addEventListener('input', () => {
            const searchValue = searchInput.value.toLowerCase();
            const rows = document.getElementById('table-body').children;
            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const cells = row.children;
                let isVisible = false;
                for (let j = 0; j < cells.length; j++) {
                    const cell = cells[j];
                    if (cell.textContent.toLowerCase().includes(searchValue)) {
                        isVisible = true;
                        break;
                    }
                }
                if (isVisible) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    </script>
</body>
</html>