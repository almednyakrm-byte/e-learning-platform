**list_students.php**

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
    <title>Students List</title>
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
            background-color: #1a1d23;
            color: #fff;
        }
        .table tr:nth-child(even) {
            background-color: #f7f7f7;
        }
        .table tr:hover {
            background-color: #f2f2f2;
        }
        .search {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 200px;
        }
        .search input[type="text"] {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="header py-4">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center">
                <a href="index.php" class="text-lg font-bold">Home</a>
                <div class="flex items-center">
                    <span class="text-lg font-bold"><?= $_SESSION['username'] ?></span>
                    <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded ml-4" onclick="location.href='logout.php'">Logout</button>
                </div>
            </div>
        </div>
    </div>
    <div class="container mx-auto px-4 py-4">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold">Students List</h2>
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_students.php'">Add New Item</button>
        </div>
        <div class="flex justify-between items-center mb-4">
            <input type="text" class="search" placeholder="Search...">
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="searchStudents()">Search</button>
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
            <tbody id="students-list">
                <!-- List of students will be displayed here -->
            </tbody>
        </table>
    </div>

    <script>
        // Fetch API to get list of students
        async function getStudents() {
            try {
                const response = await fetch('../backend/students.php');
                const data = await response.json();
                const studentsList = document.getElementById('students-list');
                studentsList.innerHTML = '';
                data.forEach(student => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${student.id}</td>
                        <td>${student.name}</td>
                        <td>${student.email}</td>
                        <td>
                            <a href="edit_students.php?id=${student.id}" class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded">Edit</a>
                            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded ml-2" onclick="deleteStudent(${student.id})">Delete</button>
                        </td>
                    `;
                    studentsList.appendChild(row);
                });
            } catch (error) {
                console.error(error);
            }
        }

        // Search students
        function searchStudents() {
            const searchInput = document.querySelector('.search input[type="text"]');
            const searchValue = searchInput.value.trim();
            if (searchValue !== '') {
                // Fetch API to search students
                async function searchStudent() {
                    try {
                        const response = await fetch('../backend/students.php?search=' + searchValue);
                        const data = await response.json();
                        const studentsList = document.getElementById('students-list');
                        studentsList.innerHTML = '';
                        data.forEach(student => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${student.id}</td>
                                <td>${student.name}</td>
                                <td>${student.email}</td>
                                <td>
                                    <a href="edit_students.php?id=${student.id}" class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded">Edit</a>
                                    <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded ml-2" onclick="deleteStudent(${student.id})">Delete</button>
                                </td>
                            `;
                            studentsList.appendChild(row);
                        });
                    } catch (error) {
                        console.error(error);
                    }
                }
                searchStudent();
            } else {
                getStudents();
            }
        }

        // Delete student
        async function deleteStudent(id) {
            if (confirm('Are you sure you want to delete this student?')) {
                try {
                    const response = await fetch('../backend/students.php', {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ id: id })
                    });
                    if (response.ok) {
                        getStudents();
                    } else {
                        console.error('Error deleting student');
                    }
                } catch (error) {
                    console.error(error);
                }
            }
        }

        // Call getStudents function to display list of students
        getStudents();
    </script>
</body>
</html>

This PHP file includes session validation, a premium Tailwind UI, and a list of students with actions to edit and delete. The search bar filters elements in real-time using the Fetch API. The delete button sends a DELETE request to the backend to delete the student.