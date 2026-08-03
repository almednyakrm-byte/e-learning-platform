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
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #333;
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
            text-align: center;
        }
        .table th {
            background-color: #333;
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
        <span class="text-indigo-500 font-bold">مرحباً, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php" class="text-red-500">تسجيل الخروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl text-slate-900 mb-4">طلاب</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_طلاب.php'">إضافة جديد</button>
        <div class="flex justify-center mb-4">
            <input type="search" class="search-bar" id="search" placeholder="بحث...">
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
                <?php
                // Fetch records from backend
                $records = json_decode(file_get_contents('../backend/طلاب.php'), true);
                foreach ($records as $record) {
                    echo '<tr>';
                    echo '<td>' . $record['name'] . '</td>';
                    echo '<td>' . $record['birthdate'] . '</td>';
                    echo '<td>' . $record['gender'] . '</td>';
                    echo '<td><button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(' . $record['id'] . ')">حذف</button></td>';
                    echo '<td><a href="edit_طلاب.php?id=' . $record['id'] . '" class="text-indigo-500 hover:text-indigo-700">تعديل</a></td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        // Search bar filtering
        const searchInput = document.getElementById('search');
        const recordsTable = document.getElementById('records');
        searchInput.addEventListener('input', () => {
            const searchValue = searchInput.value.toLowerCase();
            const records = recordsTable.getElementsByTagName('tr');
            for (let i = 0; i < records.length; i++) {
                const record = records[i];
                const name = record.cells[0].textContent.toLowerCase();
                const birthdate = record.cells[1].textContent.toLowerCase();
                const gender = record.cells[2].textContent.toLowerCase();
                if (name.includes(searchValue) || birthdate.includes(searchValue) || gender.includes(searchValue)) {
                    record.style.display = 'table-row';
                } else {
                    record.style.display = 'none';
                }
            }
        });

        // Delete record via AJAX
        function deleteRecord(id) {
            if (confirm('هل أنت متأكد من حذف هذا السجل؟')) {
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
                        alert('تم حذف السجل بنجاح!');
                        location.reload();
                    } else {
                        alert('حدث خطأ أثناء حذف السجل!');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }
    </script>
</body>
</html>

**backend/طلاب.php**

<?php
// Fetch records from database
$records = array();
// Replace with your database connection and query
$records = array(
    array('id' => 1, 'name' => 'محمد', 'birthdate' => '1990-01-01', 'gender' => 'ذكر'),
    array('id' => 2, 'name' => 'سارة', 'birthdate' => '1995-02-02', 'gender' => 'أنثى'),
    array('id' => 3, 'name' => 'عبدالله', 'birthdate' => '1980-03-03', 'gender' => 'ذكر'),
);
echo json_encode($records);

Note: This code assumes you have a backend PHP script (`backend/طلاب.php`) that fetches records from a database and returns them in JSON format. You'll need to replace the database connection and query with your actual implementation. Additionally, this code uses a simple search bar filtering mechanism, which may not be efficient for large datasets. You may want to consider using a more robust search solution, such as a full-text search engine.