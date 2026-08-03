**list_معلمين.php**

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
    <title>معلمين</title>
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
            border: 1px solid #ccc;
            border-radius: 0.5rem;
        }
        .search-bar input {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.5rem;
        }
        .search-bar input:focus {
            outline: none;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="text-indigo-500">مرحباً, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php">تسجيل خروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl text-slate-900 mb-4">معلمين</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_معلمين.php'">إضافة جديد</button>
        <div class="search-bar">
            <input type="search" id="search-input" placeholder="بحث...">
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>اسم المعلم</th>
                    <th>تليفون</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="records-table">
                <?php
                // Fetch records from backend
                $response = file_get_contents('../backend/معلمين.php');
                $records = json_decode($response, true);
                foreach ($records as $record) {
                    echo '<tr>';
                    echo '<td>' . $record['اسم المعلم'] . '</td>';
                    echo '<td>' . $record['تليفون'] . '</td>';
                    echo '<td>';
                    echo '<a href="edit_معلمين.php?id=' . $record['id'] . '" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">تعديل</a>';
                    echo '<button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(' . $record['id'] . ')">حذف</button>';
                    echo '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function searchRecords() {
            const searchInput = document.getElementById('search-input');
            const searchQuery = searchInput.value.trim();
            if (searchQuery) {
                fetch('../backend/معلمين.php?search=' + searchQuery)
                    .then(response => response.json())
                    .then(records => {
                        const recordsTable = document.getElementById('records-table');
                        recordsTable.innerHTML = '';
                        records.forEach(record => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${record['اسم المعلم']}</td>
                                <td>${record['تليفون']}</td>
                                <td>
                                    <a href="edit_معلمين.php?id=${record['id']}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">تعديل</a>
                                    <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record['id']})">حذف</button>
                                </td>
                            `;
                            recordsTable.appendChild(row);
                        });
                    });
            } else {
                fetch('../backend/معلمين.php')
                    .then(response => response.json())
                    .then(records => {
                        const recordsTable = document.getElementById('records-table');
                        recordsTable.innerHTML = '';
                        records.forEach(record => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${record['اسم المعلم']}</td>
                                <td>${record['تليفون']}</td>
                                <td>
                                    <a href="edit_معلمين.php?id=${record['id']}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">تعديل</a>
                                    <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record['id']})">حذف</button>
                                </td>
                            `;
                            recordsTable.appendChild(row);
                        });
                    });
            }
        }

        function deleteRecord(id) {
            if (confirm('هل تريد حذف هذا المعلم؟')) {
                fetch('../backend/معلمين.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف المعلم بنجاح');
                        window.location.reload();
                    } else {
                        alert('حدث خطأ أثناء حذف المعلم');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }
    </script>
</body>
</html>

This code uses the Fetch API to fetch records from the backend and display them in a table. It also includes a search bar that filters the records in real-time. The delete button sends a DELETE request to the backend to delete the record.