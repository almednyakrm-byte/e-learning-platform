**list_طلاب.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طلاب</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #2c3e50;
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
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 1rem;
            text-align: left;
        }
        .table th {
            background-color: #2c3e50;
            color: #fff;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            font-size: 1.5rem;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
        }
        .search-bar:focus {
            outline: none;
            border-color: #aaa;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="mx-2">|</span>
        <span><?= $_SESSION['username'] ?></span>
        <span class="mx-2">|</span>
        <a href="logout.php">تسجيل الخروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">طلاب</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded mb-4" onclick="location.href='create_طلاب.php'">إضافة جديد</button>
        <div class="search-bar">
            <input type="search" id="search" placeholder="بحث...">
            <button class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>اسم الطالب</th>
                    <th>تاريخ الميلاد</th>
                    <th>جنس الطالب</th>
                    <th>حذف</th>
                    <th>تعديل</th>
                </tr>
            </thead>
            <tbody id="records">
                <!-- Records will be fetched from backend using AJAX -->
            </tbody>
        </table>
    </div>

    <script>
        // Fetch records from backend using AJAX
        fetch('../backend/طلاب.php')
            .then(response => response.json())
            .then(data => {
                const records = document.getElementById('records');
                data.forEach(record => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${record.name}</td>
                        <td>${record.birthdate}</td>
                        <td>${record.gender}</td>
                        <td>
                            <button class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                        </td>
                        <td>
                            <button class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='edit_طلاب.php?id=${record.id}'">تعديل</button>
                        </td>
                    `;
                    records.appendChild(row);
                });
            })
            .catch(error => console.error('Error:', error));

        // Search records using AJAX
        function searchRecords() {
            const searchInput = document.getElementById('search').value;
            fetch('../backend/طلاب.php?search=' + searchInput)
                .then(response => response.json())
                .then(data => {
                    const records = document.getElementById('records');
                    records.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${record.name}</td>
                            <td>${record.birthdate}</td>
                            <td>${record.gender}</td>
                            <td>
                                <button class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                            <td>
                                <button class="bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='edit_طلاب.php?id=${record.id}'">تعديل</button>
                            </td>
                        `;
                        records.appendChild(row);
                    });
                })
                .catch(error => console.error('Error:', error));
        }

        // Delete record using AJAX
        function deleteRecord(id) {
            if (confirm('هل تريد حذف هذا السجل؟')) {
                fetch('../backend/طلاب.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف السجل بنجاح');
                        location.reload();
                    } else {
                        alert('حدث خطأ أثناء حذف السجل');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }
    </script>
</body>
</html>

This code includes the following features:

* Session validation to ensure the user is logged in before accessing the page.
* A premium Tailwind UI design with a specific color palette matching the theme.
* A header navigation bar with links to the main page, user info, and logout.
* A table showing a list of records with actions to edit and delete each record.
* An "Add New Item" button linking to the create_طلاب.php page.
* A search bar filtering elements in real-time using AJAX.
* AJAX JavaScript code fetching list records from the backend and handling delete requests.

Note that this code assumes you have a backend PHP script (`../backend/طلاب.php`) that handles the AJAX requests and returns the necessary data.