**list_امتحانات.php**

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
    <title>امتحانات</title>
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
            background-color: #fff;
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: 0.25rem;
        }
        .table th, .table td {
            padding: 0.5rem;
            border-bottom: 1px solid #ddd;
        }
        .table th {
            background-color: #f0f0f0;
        }
        .table th, .table td {
            text-align: center;
        }
        .table th {
            font-weight: bold;
        }
        .search-bar {
            padding: 1rem;
            border: 1px solid #ccc;
            border-radius: 0.25rem;
        }
        .search-bar input[type="search"] {
            width: 100%;
            padding: 0.5rem;
            font-size: 1rem;
            border: none;
            border-radius: 0.25rem;
        }
        .search-bar input[type="search"]:focus {
            outline: none;
            box-shadow: 0 0 0 0.25rem rgba(0, 0, 0, 0.25);
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="text-indigo-500">مرحباً</span> <?php echo $_SESSION['username']; ?> |
        <a href="logout.php">تسجيل خروج</a>
    </div>
    <div class="container mx-auto p-4">
        <div class="flex justify-between mb-4">
            <h1 class="text-2xl text-slate-900">امتحانات</h1>
            <a href="create_امتحانات.php" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">إضافة جديد</a>
        </div>
        <div class="search-bar">
            <input type="search" id="search" placeholder="بحث...">
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table w-full">
            <thead>
                <tr>
                    <th>رقم</th>
                    <th>الاسم</th>
                    <th>الوصف</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <?php
                // Fetch records from backend
                $records = json_decode(file_get_contents('../backend/امتحانات.php'), true);
                foreach ($records as $record) {
                    echo '<tr>';
                    echo '<td>' . $record['id'] . '</td>';
                    echo '<td>' . $record['name'] . '</td>';
                    echo '<td>' . $record['description'] . '</td>';
                    echo '<td>';
                    echo '<a href="edit_امتحانات.php?id=' . $record['id'] . '" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">تعديل</a>';
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
            fetch('../backend/امتحانات.php?search=' + search)
                .then(response => response.json())
                .then(data => {
                    const records = document.getElementById('records');
                    records.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${record.id}</td>
                            <td>${record.name}</td>
                            <td>${record.description}</td>
                            <td>
                                <a href="edit_امتحانات.php?id=${record.id}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">تعديل</a>
                                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        `;
                        records.appendChild(row);
                    });
                });
        }

        function deleteRecord(id) {
            if (confirm('هل أنت متأكد من حذف هذا السجل؟')) {
                fetch('../backend/امتحانات.php', {
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
                        window.location.reload();
                    } else {
                        alert('حدث خطأ أثناء حذف السجل');
                    }
                });
            }
        }
    </script>
</body>
</html>

**backend/امتحانات.php**

<?php
// Fetch records from database
$records = array();
$records[] = array('id' => 1, 'name' => 'سجل 1', 'description' => 'وصف سجل 1');
$records[] = array('id' => 2, 'name' => 'سجل 2', 'description' => 'وصف سجل 2');
$records[] = array('id' => 3, 'name' => 'سجل 3', 'description' => 'وصف سجل 3');

// Search functionality
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $records = array_filter($records, function($record) use ($search) {
        return strpos($record['name'], $search) !== false || strpos($record['description'], $search) !== false;
    });
}

// Output records as JSON
header('Content-Type: application/json');
echo json_encode($records);