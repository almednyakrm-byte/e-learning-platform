**list_اساتذة.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اساتذة</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f7f7;
        }
        .bg-slate-900 {
            background-color: #1a1d23;
        }
        .text-indigo-500 {
            color: #6b7280;
        }
    </style>
</head>
<body class="bg-slate-900">
    <div class="container mx-auto p-4">
        <header class="bg-indigo-500 p-4 mb-4">
            <nav class="flex justify-between">
                <a href="index.php" class="text-indigo-500 hover:text-white">Back to Index</a>
                <div class="flex items-center">
                    <span class="text-indigo-500 mr-2">Welcome, <?php echo $_SESSION['username']; ?></span>
                    <a href="logout.php" class="text-indigo-500 hover:text-white">Logout</a>
                </div>
            </nav>
        </header>
        <div class="bg-white p-4 rounded shadow-md">
            <h2 class="text-lg font-bold mb-2">List of Asatidha</h2>
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded mb-4" onclick="location.href='create_اساتذة.php'">Add New Item</button>
            <div class="flex justify-between mb-4">
                <input type="search" id="search" class="w-full p-2 pl-10 text-sm text-gray-700 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Search...">
                <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">Search</button>
            </div>
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Name</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody id="records">
                    <!-- Records will be loaded here -->
                </tbody>
            </table>
        </div>
    </div>

    <script>
        const searchInput = document.getElementById('search');
        const recordsContainer = document.getElementById('records');

        function searchRecords() {
            const searchQuery = searchInput.value.trim();
            if (searchQuery) {
                fetch('../backend/اساتذة.php', {
                    method: 'GET',
                    params: { search: searchQuery }
                })
                .then(response => response.json())
                .then(data => {
                    const records = data.records;
                    recordsContainer.innerHTML = '';
                    records.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td class="px-4 py-2">${record.id}</td>
                            <td class="px-4 py-2">${record.name}</td>
                            <td class="px-4 py-2">
                                <a href="edit_اساتذة.php?id=${record.id}" class="text-indigo-500 hover:text-white">Edit</a>
                                <button class="text-red-500 hover:text-red-700" onclick="deleteRecord(${record.id})">Delete</button>
                            </td>
                        `;
                        recordsContainer.appendChild(row);
                    });
                });
            } else {
                loadRecords();
            }
        }

        function loadRecords() {
            fetch('../backend/اساتذة.php')
            .then(response => response.json())
            .then(data => {
                const records = data.records;
                recordsContainer.innerHTML = '';
                records.forEach(record => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-4 py-2">${record.id}</td>
                        <td class="px-4 py-2">${record.name}</td>
                        <td class="px-4 py-2">
                            <a href="edit_اساتذة.php?id=${record.id}" class="text-indigo-500 hover:text-white">Edit</a>
                            <button class="text-red-500 hover:text-red-700" onclick="deleteRecord(${record.id})">Delete</button>
                        </td>
                    `;
                    recordsContainer.appendChild(row);
                });
            });
        }

        function deleteRecord(id) {
            if (confirm('Are you sure you want to delete this record?')) {
                fetch('../backend/اساتذة.php', {
                    method: 'DELETE',
                    params: { id }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadRecords();
                    } else {
                        alert('Error deleting record');
                    }
                });
            }
        }

        loadRecords();
    </script>
</body>
</html>

**backend/اساتذة.php**

<?php
// Database connection
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Search query
if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
    $query = "SELECT * FROM asatidha WHERE name LIKE '%$searchQuery%'";
} else {
    $query = "SELECT * FROM asatidha";
}

// Execute query
$result = $conn->query($query);

// Fetch records
$records = array();
while ($row = $result->fetch_assoc()) {
    $records[] = $row;
}

// Output records
header('Content-Type: application/json');
echo json_encode(array('records' => $records));

// Delete record
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM asatidha WHERE id = '$id'";
    $conn->query($query);
    echo json_encode(array('success' => true));
}

// Close connection
$conn->close();
?>