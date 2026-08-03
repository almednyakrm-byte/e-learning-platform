**list_مدرسين.php**

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
    <title>مدرسين</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #2d3748;
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
            background-color: #2d3748;
            color: #fff;
        }
        .table td {
            background-color: #f7f7f7;
        }
        .table td a {
            text-decoration: none;
            color: #2d3748;
        }
        .table td a:hover {
            color: #333;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: 0.5rem;
            background-color: #f7f7f7;
        }
        .search-bar input[type="search"] {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.5rem;
            background-color: #f7f7f7;
        }
        .search-bar input[type="search"]:focus {
            outline: none;
            border-color: #2d3748;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="text-lg font-bold">مدرسين</span>
        <a href="logout.php">تسجيل الخروج</a>
        <span class="text-lg font-bold">مرحباً <?php echo $_SESSION['username']; ?></span>
    </div>
    <div class="container mx-auto p-4">
        <div class="flex justify-between mb-4">
            <h1 class="text-2xl font-bold">قائمة مدرسين</h1>
            <a href="create_مدرسين.php" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">اضافة جديد</a>
        </div>
        <div class="search-bar">
            <input type="search" id="search" placeholder="بحث...">
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>اسم المدرس</th>
                    <th>البريد الإلكتروني</th>
                    <th>العمليات</th>
                </tr>
            </thead>
            <tbody id="records">
                <?php
                // Fetch records from backend
                $records = json_decode(file_get_contents('../backend/مدرسين.php'), true);
                foreach ($records as $record) {
                    echo '<tr>';
                    echo '<td>' . $record['name'] . '</td>';
                    echo '<td>' . $record['email'] . '</td>';
                    echo '<td>';
                    echo '<a href="edit_مدرسين.php?id=' . $record['id'] . '">تعديل</a>';
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
            const search = document.getElementById('search').value;
            fetch('../backend/مدرسين.php?search=' + search)
                .then(response => response.json())
                .then(data => {
                    const records = document.getElementById('records');
                    records.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${record.name}</td>
                            <td>${record.email}</td>
                            <td>
                                <a href="edit_مدرسين.php?id=${record.id}">تعديل</a>
                                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        `;
                        records.appendChild(row);
                    });
                });
        }

        function deleteRecord(id) {
            if (confirm('هل تريد حذف هذا المدرس؟')) {
                fetch('../backend/مدرسين.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف المدرس بنجاح');
                        window.location.reload();
                    } else {
                        alert('حدث خطأ أثناء حذف المدرس');
                    }
                })
                .catch(error => console.error(error));
            }
        }
    </script>
</body>
</html>

**backend/مدرسين.php**

<?php
// Fetch records from database
$records = array();
// Replace with your database connection and query
$records = array(
    array('id' => 1, 'name' => 'مدرس 1', 'email' => 'madr1@example.com'),
    array('id' => 2, 'name' => 'مدرس 2', 'email' => 'madr2@example.com'),
    array('id' => 3, 'name' => 'مدرس 3', 'email' => 'madr3@example.com')
);

// Search functionality
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $records = array_filter($records, function($record) use ($search) {
        return strpos($record['name'], $search) !== false || strpos($record['email'], $search) !== false;
    });
}

// Output records in JSON format
header('Content-Type: application/json');
echo json_encode($records);