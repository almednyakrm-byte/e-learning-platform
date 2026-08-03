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
    <title>Students List</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <header class="bg-blue-500 text-white p-4">
        <nav class="flex justify-between">
            <a href="index.php" class="text-lg font-bold">Back to Index</a>
            <span class="text-lg font-bold">Welcome, <?php echo $current_user; ?></span>
            <a href="?logout=true" class="text-lg font-bold text-orange-300">Logout</a>
        </nav>
    </header>
    <main class="p-4">
        <h1 class="text-3xl font-bold mb-4">Students List</h1>
        <input type="search" id="search" class="w-full p-2 pl-10 text-sm text-gray-700" placeholder="Search students...">
        <table id="students-table" class="w-full table-auto mt-4">
            <thead class="bg-blue-500 text-white">
                <tr>
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody id="students-tbody">
                <!-- Table content will be populated by JavaScript -->
            </tbody>
        </table>
        <a href="create_students.php" class="bg-orange-300 hover:bg-orange-400 text-white font-bold py-2 px-4 rounded mt-4">Add New Item</a>
    </main>

    <script>
        // Fetch students data from backend
        fetch('../backend/students.php')
            .then(response => response.json())
            .then(data => {
                const studentsTable = document.getElementById('students-tbody');
                data.forEach(student => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-4 py-2">${student.id}</td>
                        <td class="px-4 py-2">${student.name}</td>
                        <td class="px-4 py-2">
                            <a href="edit_students.php?id=${student.id}" class="text-blue-500 hover:text-blue-700">Edit</a>
                            <button class="text-red-500 hover:text-red-700 ml-2" onclick="deleteStudent(${student.id})">Delete</button>
                        </td>
                    `;
                    studentsTable.appendChild(row);
                });
            });

        // Delete student
        function deleteStudent(id) {
            fetch('../backend/students.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove deleted student from table
                    const rows = document.getElementById('students-tbody').children;
                    for (let i = 0; i < rows.length; i++) {
                        if (rows[i].children[0].textContent == id) {
                            rows[i].remove();
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
            const rows = document.getElementById('students-tbody').children;
            for (let i = 0; i < rows.length; i++) {
                const rowText = rows[i].textContent.toLowerCase();
                if (rowText.includes(searchValue)) {
                    rows[i].style.display = '';
                } else {
                    rows[i].style.display = 'none';
                }
            }
        });
    </script>
</body>
</html>