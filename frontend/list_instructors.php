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
    session_unset();
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
    <title>Instructors List</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <header class="bg-blue-500 text-white p-4">
        <nav class="flex justify-between">
            <a href="index.php" class="text-lg font-bold">Home</a>
            <div class="flex items-center">
                <span class="mr-4">Welcome, <?php echo $current_user; ?></span>
                <a href="?logout" class="bg-orange-300 hover:bg-orange-400 text-white font-bold py-2 px-4 rounded">Logout</a>
            </div>
        </nav>
    </header>
    <main class="p-4">
        <h1 class="text-3xl font-bold mb-4">Instructors List</h1>
        <div class="flex justify-between mb-4">
            <a href="create_instructors.php" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">Add New Item</a>
            <input type="search" id="search" class="px-4 py-2 border border-gray-300 rounded" placeholder="Search...">
        </div>
        <table id="instructors-table" class="w-full table-auto border border-gray-300">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody id="table-body">
                <!-- Table data will be populated here -->
            </tbody>
        </table>
    </main>

    <script>
        // Fetch API to get instructors list
        fetch('../backend/instructors.php')
            .then(response => response.json())
            .then(data => {
                const tableBody = document.getElementById('table-body');
                const searchInput = document.getElementById('search');

                // Function to render table data
                function renderTable(data) {
                    tableBody.innerHTML = '';
                    data.forEach(instructor => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td class="px-4 py-2">${instructor.id}</td>
                            <td class="px-4 py-2">${instructor.name}</td>
                            <td class="px-4 py-2">
                                <a href="edit_instructors.php?id=${instructor.id}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">Edit</a>
                                <button class="bg-orange-300 hover:bg-orange-400 text-white font-bold py-2 px-4 rounded" onclick="deleteInstructor(${instructor.id})">Delete</button>
                            </td>
                        `;
                        tableBody.appendChild(row);
                    });
                }

                // Render initial table data
                renderTable(data);

                // Search functionality
                searchInput.addEventListener('input', () => {
                    const filteredData = data.filter(instructor => {
                        return instructor.name.toLowerCase().includes(searchInput.value.toLowerCase());
                    });
                    renderTable(filteredData);
                });
            });

        // Delete instructor function
        function deleteInstructor(id) {
            fetch('../backend/instructors.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                // Update table data after deletion
                fetch('../backend/instructors.php')
                    .then(response => response.json())
                    .then(data => {
                        const tableBody = document.getElementById('table-body');
                        const searchInput = document.getElementById('search');

                        // Function to render table data
                        function renderTable(data) {
                            tableBody.innerHTML = '';
                            data.forEach(instructor => {
                                const row = document.createElement('tr');
                                row.innerHTML = `
                                    <td class="px-4 py-2">${instructor.id}</td>
                                    <td class="px-4 py-2">${instructor.name}</td>
                                    <td class="px-4 py-2">
                                        <a href="edit_instructors.php?id=${instructor.id}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">Edit</a>
                                        <button class="bg-orange-300 hover:bg-orange-400 text-white font-bold py-2 px-4 rounded" onclick="deleteInstructor(${instructor.id})">Delete</button>
                                    </td>
                                `;
                                tableBody.appendChild(row);
                            });
                        }

                        // Render updated table data
                        renderTable(data);

                        // Search functionality
                        searchInput.addEventListener('input', () => {
                            const filteredData = data.filter(instructor => {
                                return instructor.name.toLowerCase().includes(searchInput.value.toLowerCase());
                            });
                            renderTable(filteredData);
                        });
                    });
            });
        }
    </script>
</body>
</html>