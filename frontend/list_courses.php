**list_courses.php**

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
    <title>Courses</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f5;
        }
        .bg-slate-900 {
            background-color: #1a1d23;
        }
        .text-indigo-500 {
            color: #6b7280;
        }
    </style>
</head>
<body class="bg-slate-900 text-indigo-500">
    <div class="container mx-auto p-4">
        <header class="flex justify-between items-center mb-4">
            <a href="index.php" class="text-lg font-bold">Home</a>
            <div class="flex items-center">
                <span class="mr-2">Welcome, <?php echo $_SESSION['username']; ?></span>
                <a href="logout.php" class="text-red-500 hover:text-red-700">Logout</a>
            </div>
        </header>
        <main class="bg-white rounded-lg p-4 shadow-md">
            <h2 class="text-lg font-bold mb-2">Courses</h2>
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded mb-4" onclick="location.href='create_courses.php'">Add New Item</button>
            <div class="flex justify-between items-center mb-4">
                <input type="search" id="search" class="w-full p-2 pl-10 text-sm text-gray-700 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Search...">
                <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="searchCourses()">Search</button>
            </div>
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Name</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody id="courses-list">
                    <!-- Courses will be listed here -->
                </tbody>
            </table>
        </main>
    </div>

    <script>
        // Fetch courses list
        async function fetchCourses() {
            try {
                const response = await fetch('../backend/courses.php', { method: 'GET' });
                const data = await response.json();
                const coursesList = document.getElementById('courses-list');
                data.forEach(course => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-4 py-2">${course.id}</td>
                        <td class="px-4 py-2">${course.name}</td>
                        <td class="px-4 py-2">
                            <a href="edit_courses.php?id=${course.id}" class="text-blue-500 hover:text-blue-700">Edit</a>
                            <button class="text-red-500 hover:text-red-700" onclick="deleteCourse(${course.id})">Delete</button>
                        </td>
                    `;
                    coursesList.appendChild(row);
                });
            } catch (error) {
                console.error(error);
            }
        }

        // Search courses
        function searchCourses() {
            const searchInput = document.getElementById('search');
            const searchQuery = searchInput.value.trim();
            if (searchQuery) {
                fetchCourses(searchQuery);
            } else {
                fetchCourses();
            }
        }

        // Delete course
        async function deleteCourse(id) {
            try {
                const response = await fetch('../backend/courses.php', { method: 'DELETE', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ id }) });
                if (response.ok) {
                    const coursesList = document.getElementById('courses-list');
                    coursesList.innerHTML = '';
                    fetchCourses();
                } else {
                    console.error('Error deleting course');
                }
            } catch (error) {
                console.error(error);
            }
        }

        // Initialize courses list
        fetchCourses();
    </script>
</body>
</html>


**courses.php (backend)**

<?php
// Database connection
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// GET courses list
if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
    $sql = "SELECT * FROM courses WHERE name LIKE '%$searchQuery%'";
} else {
    $sql = "SELECT * FROM courses";
}

$result = $conn->query($sql);

// Check if result is empty
if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        echo json_encode($row);
    }
} else {
    echo json_encode(array());
}

// Close connection
$conn->close();
?>


**create_courses.php**

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
    <title>Create Course</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f5;
        }
        .bg-slate-900 {
            background-color: #1a1d23;
        }
        .text-indigo-500 {
            color: #6b7280;
        }
    </style>
</head>
<body class="bg-slate-900 text-indigo-500">
    <div class="container mx-auto p-4">
        <header class="flex justify-between items-center mb-4">
            <a href="index.php" class="text-lg font-bold">Home</a>
            <div class="flex items-center">
                <span class="mr-2">Welcome, <?php echo $_SESSION['username']; ?></span>
                <a href="logout.php" class="text-red-500 hover:text-red-700">Logout</a>
            </div>
        </header>
        <main class="bg-white rounded-lg p-4 shadow-md">
            <h2 class="text-lg font-bold mb-2">Create Course</h2>
            <form action="../backend/create_course.php" method="POST">
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Name:</label>
                    <input type="text" id="name" name="name" class="block w-full p-2 pl-10 text-sm text-gray-700 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>
                <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" type="submit">Create</button>
            </form>
        </main>
    </div>
</body>
</html>


**edit_courses.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get course ID
$id = $_GET['id'];

// Database connection
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get course data
$sql = "SELECT * FROM courses WHERE id = '$id'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Course</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f5;
        }
        .bg-slate-900 {
            background-color: #1a1d23;
        }
        .text-indigo-500 {
            color: #6b7280;
        }
    </style>
</head>
<body class="bg-slate-900 text-indigo-500">
    <div class="container mx-auto p-4">
        <header class="flex justify-between items-center mb-4">
            <a href="index.php" class="text-lg font-bold">Home</a>
            <div class="flex items-center">
                <span class