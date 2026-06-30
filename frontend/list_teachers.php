**list_teachers.php**

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
    <title>Teachers List</title>
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
            padding: 8px;
            text-align: left;
        }
        .table th {
            background-color: #1a1d23;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="container mx-auto p-4">
        <div class="header flex justify-between items-center py-4">
            <a href="index.php" class="text-lg font-bold">Dashboard</a>
            <div class="flex items-center">
                <span class="text-lg font-bold"><?= $_SESSION['username'] ?></span>
                <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded ml-4" onclick="location.href='logout.php'">Logout</button>
            </div>
        </div>
        <div class="flex justify-between items-center py-4">
            <h2 class="text-lg font-bold">Teachers List</h2>
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_teachers.php'">Add New Item</button>
        </div>
        <div class="flex justify-between items-center py-4">
            <input type="search" id="search" class="w-full p-2 text-lg font-bold border border-gray-300 rounded" placeholder="Search...">
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="searchTeachers()">Search</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="teachers-list">
                <?php
                // Fetch teachers list from backend
                $response = file_get_contents('../backend/teachers.php');
                $teachers = json_decode($response, true);
                foreach ($teachers as $teacher) {
                    ?>
                    <tr>
                        <td><?= $teacher['id'] ?></td>
                        <td><?= $teacher['name'] ?></td>
                        <td><?= $teacher['email'] ?></td>
                        <td>
                            <a href="edit_teachers.php?id=<?= $teacher['id'] ?>" class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded mr-2">Edit</a>
                            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="deleteTeacher(<?= $teacher['id'] ?>)">Delete</button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function searchTeachers() {
            const searchValue = document.getElementById('search').value;
            fetch('../backend/teachers.php?search=' + searchValue)
                .then(response => response.json())
                .then(teachers => {
                    const teachersList = document.getElementById('teachers-list');
                    teachersList.innerHTML = '';
                    teachers.forEach(teacher => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${teacher.id}</td>
                            <td>${teacher.name}</td>
                            <td>${teacher.email}</td>
                            <td>
                                <a href="edit_teachers.php?id=${teacher.id}" class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded mr-2">Edit</a>
                                <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="deleteTeacher(${teacher.id})">Delete</button>
                            </td>
                        `;
                        teachersList.appendChild(row);
                    });
                });
        }

        function deleteTeacher(id) {
            if (confirm('Are you sure you want to delete this teacher?')) {
                fetch('../backend/teachers.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Teacher deleted successfully!');
                        location.reload();
                    } else {
                        alert('Error deleting teacher!');
                    }
                })
                .catch(error => console.error(error));
            }
        }
    </script>
</body>
</html>

**backend/teachers.php**

<?php
// Fetch teachers list from database
$teachers = array(
    array('id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com'),
    array('id' => 2, 'name' => 'Jane Doe', 'email' => 'jane@example.com'),
    array('id' => 3, 'name' => 'Bob Smith', 'email' => 'bob@example.com')
);

// Search functionality
if (isset($_GET['search'])) {
    $searchValue = $_GET['search'];
    $teachers = array_filter($teachers, function($teacher) use ($searchValue) {
        return strpos($teacher['name'], $searchValue) !== false || strpos($teacher['email'], $searchValue) !== false;
    });
}

// Delete teacher functionality
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = json_decode(file_get_contents('php://input'), true)['id'];
    $teachers = array_filter($teachers, function($teacher) use ($id) {
        return $teacher['id'] !== $id;
    });
    echo json_encode(array('success' => true));
    exit;
}

// Return teachers list as JSON
echo json_encode($teachers);