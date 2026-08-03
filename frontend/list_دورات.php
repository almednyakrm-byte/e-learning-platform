**list_دورات.php**

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
    <title>دورات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            direction: rtl;
        }
        .bg-slate-900 {
            background-color: #1A1D23;
        }
        .text-indigo-500 {
            color: #6B5CFF;
        }
    </style>
</head>
<body class="bg-slate-900">
    <div class="container mx-auto p-4">
        <header class="flex justify-between items-center mb-4">
            <a href="index.php" class="text-indigo-500 hover:text-white">الرئيسية</a>
            <div class="flex items-center">
                <span class="text-indigo-500">مرحباً <?= $_SESSION['username'] ?></span>
                <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded ml-4" onclick="location.href='logout.php'">تسجيل الخروج</button>
            </div>
        </header>
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-indigo-500 text-3xl">دورات</h1>
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_دورات.php'">إضافة جديد</button>
        </div>
        <div class="flex justify-between items-center mb-4">
            <input type="search" id="search" class="bg-gray-800 text-gray-300 rounded py-2 px-4 w-full" placeholder="بحث...">
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="w-full border-collapse border border-gray-800">
            <thead>
                <tr>
                    <th class="border border-gray-800 p-4">رقم الدورة</th>
                    <th class="border border-gray-800 p-4">اسم الدورة</th>
                    <th class="border border-gray-800 p-4">الإجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <?php
                // Fetch records from backend
                $records = fetchRecords();
                foreach ($records as $record) {
                    ?>
                    <tr>
                        <td class="border border-gray-800 p-4"><?= $record['id'] ?></td>
                        <td class="border border-gray-800 p-4"><?= $record['name'] ?></td>
                        <td class="border border-gray-800 p-4">
                            <a href="edit_دورات.php?id=<?= $record['id'] ?>" class="text-indigo-500 hover:text-white">تعديل</a>
                            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-4" onclick="deleteRecord(<?= $record['id'] ?>)">حذف</button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function searchRecords() {
            const search = document.getElementById('search').value;
            fetch('../backend/دورات.php?search=' + search)
                .then(response => response.json())
                .then(data => {
                    const records = document.getElementById('records');
                    records.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td class="border border-gray-800 p-4">${record.id}</td>
                            <td class="border border-gray-800 p-4">${record.name}</td>
                            <td class="border border-gray-800 p-4">
                                <a href="edit_دورات.php?id=${record.id}" class="text-indigo-500 hover:text-white">تعديل</a>
                                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-4" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        `;
                        records.appendChild(row);
                    });
                });
        }

        function deleteRecord(id) {
            if (confirm('هل تريد حذف الدورة؟')) {
                fetch('../backend/دورات.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف الدورة بنجاح');
                        location.reload();
                    } else {
                        alert('حدث خطأ');
                    }
                });
            }
        }

        function fetchRecords() {
            return fetch('../backend/دورات.php')
                .then(response => response.json())
                .then(data => data.records);
        }
    </script>
</body>
</html>


**backend/دورات.php**

<?php
// Fetch records from database
$records = array();
$records = [
    ['id' => 1, 'name' => 'دورة 1'],
    ['id' => 2, 'name' => 'دورة 2'],
    ['id' => 3, 'name' => 'دورة 3']
];

// Search records
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $records = array_filter($records, function($record) use ($search) {
        return strpos($record['name'], $search) !== false;
    });
}

// DELETE request
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = json_decode(file_get_contents('php://input'), true)['id'];
    // Delete record from database
    // ...
    echo json_encode(['success' => true]);
}

// Return records
echo json_encode(['records' => $records]);
?>