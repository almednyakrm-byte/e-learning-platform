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
        .header {
            background-color: #1a1d23;
            color: #fff;
        }
        .header a {
            color: #fff;
        }
        .header a:hover {
            color: #ccc;
        }
        .table {
            border-collapse: collapse;
            width: 100%;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .table th {
            background-color: #f0f0f0;
        }
        .search-bar {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 300px;
        }
    </style>
</head>
<body>
    <div class="header flex justify-between items-center p-4">
        <a href="index.php" class="text-lg font-bold">Home</a>
        <div class="flex items-center">
            <span class="text-lg font-bold"><?= $_SESSION['username'] ?></span>
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded ml-4" onclick="location.href='logout.php'">Logout</button>
        </div>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">اساتذة</h1>
        <button class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded mb-4" onclick="location.href='create_اساتذة.php'">Add New Item</button>
        <div class="search-bar mb-4">
            <input type="search" id="search" placeholder="Search..." class="w-full p-2">
            <button class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">Search</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="records">
                <?php
                // Fetch records from backend
                $records = fetchRecords();
                foreach ($records as $record) {
                    ?>
                    <tr>
                        <td><?= $record['id'] ?></td>
                        <td><?= $record['name'] ?></td>
                        <td>
                            <a href="edit_اساتذة.php?id=<?= $record['id'] ?>" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded mr-2">Edit</a>
                            <button class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(<?= $record['id'] ?>)">Delete</button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        // Fetch records from backend
        async function fetchRecords() {
            const response = await fetch('../backend/اساتذة.php', { method: 'GET' });
            const data = await response.json();
            return data;
        }

        // Search records
        function searchRecords() {
            const searchValue = document.getElementById('search').value;
            fetchRecords().then(data => {
                const records = document.getElementById('records');
                records.innerHTML = '';
                data.forEach(record => {
                    if (record.name.includes(searchValue)) {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${record.id}</td>
                            <td>${record.name}</td>
                            <td>
                                <a href="edit_اساتذة.php?id=${record.id}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded mr-2">Edit</a>
                                <button class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">Delete</button>
                            </td>
                        `;
                        records.appendChild(row);
                    }
                });
            });
        }

        // Delete record
        async function deleteRecord(id) {
            const response = await fetch('../backend/اساتذة.php', { method: 'DELETE', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ id }) });
            if (response.ok) {
                const records = document.getElementById('records');
                records.innerHTML = '';
                fetchRecords().then(data => {
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${record.id}</td>
                            <td>${record.name}</td>
                            <td>
                                <a href="edit_اساتذة.php?id=${record.id}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded mr-2">Edit</a>
                                <button class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">Delete</button>
                            </td>
                        `;
                        records.appendChild(row);
                    });
                });
            } else {
                alert('Error deleting record');
            }
        }
    </script>
</body>
</html>


**backend/اساتذة.php**

<?php
// Database connection
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch records
$query = "SELECT * FROM teachers";
$result = $conn->query($query);

// Get records as JSON
$records = array();
while ($row = $result->fetch_assoc()) {
    $records[] = $row;
}
echo json_encode($records);

// Delete record
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = $_POST['id'];
    $query = "DELETE FROM teachers WHERE id = '$id'";
    $conn->query($query);
    echo 'Record deleted successfully';
}

// Close connection
$conn->close();
?>