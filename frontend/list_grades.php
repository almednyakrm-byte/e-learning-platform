**list_grades.php**

<?php
session_start();

// Check if user is authenticated
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
    <title>Grades Management</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f7f7;
        }
        .header {
            background-color: #1a1d23;
            color: #fff;
            padding: 1rem;
            text-align: center;
        }
        .header a {
            color: #fff;
            text-decoration: none;
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
            padding: 1rem;
            text-align: left;
        }
        .table th {
            background-color: #1a1d23;
            color: #fff;
        }
        .search-bar {
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: 0.5rem;
            width: 50%;
        }
        .search-bar input {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.5rem;
        }
        .search-bar input:focus {
            outline: none;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">Back to Index</a>
        <span>Welcome, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php">Logout</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">Grades Management</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_grades.php'">Add New Item</button>
        <div class="flex justify-center mb-4">
            <input type="search" class="search-bar" placeholder="Search..." id="search-input">
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="searchGrades()">Search</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Student Name</th>
                    <th>Grade</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="grades-table">
                <!-- Grades will be loaded here -->
            </tbody>
        </table>
    </div>

    <script>
        const searchInput = document.getElementById('search-input');
        const gradesTable = document.getElementById('grades-table');

        function searchGrades() {
            const searchQuery = searchInput.value.trim();
            if (searchQuery !== '') {
                fetch('../backend/grades.php?search=' + searchQuery)
                    .then(response => response.json())
                    .then(data => {
                        const gradesHtml = data.map(grade => `
                            <tr>
                                <td>${grade.id}</td>
                                <td>${grade.student_name}</td>
                                <td>${grade.grade}</td>
                                <td>
                                    <a href="edit_grades.php?id=${grade.id}" class="text-indigo-500 hover:text-indigo-700">Edit</a>
                                    <button class="text-red-500 hover:text-red-700" onclick="deleteGrade(${grade.id})">Delete</button>
                                </td>
                            </tr>
                        `).join('');
                        gradesTable.innerHTML = gradesHtml;
                    });
            } else {
                loadGrades();
            }
        }

        function loadGrades() {
            fetch('../backend/grades.php')
                .then(response => response.json())
                .then(data => {
                    const gradesHtml = data.map(grade => `
                        <tr>
                            <td>${grade.id}</td>
                            <td>${grade.student_name}</td>
                            <td>${grade.grade}</td>
                            <td>
                                <a href="edit_grades.php?id=${grade.id}" class="text-indigo-500 hover:text-indigo-700">Edit</a>
                                <button class="text-red-500 hover:text-red-700" onclick="deleteGrade(${grade.id})">Delete</button>
                            </td>
                        </tr>
                    `).join('');
                    gradesTable.innerHTML = gradesHtml;
                });
        }

        function deleteGrade(id) {
            if (confirm('Are you sure you want to delete this grade?')) {
                fetch('../backend/grades.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadGrades();
                    } else {
                        alert('Error deleting grade');
                    }
                });
            }
        }

        loadGrades();
    </script>
</body>
</html>


**grades.php (backend)**

<?php
require_once 'db.php';

if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
    $grades = array();
    $query = "SELECT * FROM grades WHERE student_name LIKE '%$searchQuery%' OR grade LIKE '%$searchQuery%'";
} else {
    $grades = array();
    $query = "SELECT * FROM grades";
}

$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_assoc($result)) {
    $grades[] = $row;
}

echo json_encode($grades);
?>


**db.php (backend)**

<?php
$conn = mysqli_connect('localhost', 'username', 'password', 'database');
if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
}
?>