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
            background-color: #f5f5f5;
        }
        .bg-slate-900 {
            background-color: #1a1d23;
        }
        .text-indigo-500 {
            color: #6b6ecf;
        }
    </style>
</head>
<body class="bg-slate-900 text-white">
    <header class="bg-indigo-500 p-4">
        <nav class="container mx-auto flex justify-between items-center">
            <a href="index.php" class="text-lg font-bold">الصفحة الرئيسية</a>
            <div class="flex items-center">
                <span class="text-lg font-bold"><?= $_SESSION['username'] ?></span>
                <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded ml-4" onclick="location.href='logout.php'">تسجيل الخروج</button>
            </div>
        </nav>
    </header>
    <main class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">طلاب</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded mb-4" onclick="location.href='create_طلاب.php'">إضافة جديد</button>
        <div class="mb-4">
            <input type="search" id="search" class="w-full p-2 mb-2 text-lg font-bold" placeholder="بحث...">
        </div>
        <table class="w-full border-collapse border border-slate-400">
            <thead>
                <tr>
                    <th class="border border-slate-400 p-2">اسم الطالب</th>
                    <th class="border border-slate-400 p-2">تاريخ الميلاد</th>
                    <th class="border border-slate-400 p-2">الإجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <!-- Records will be loaded here -->
            </tbody>
        </table>
    </main>
    <script>
        const searchInput = document.getElementById('search');
        const recordsTable = document.getElementById('records');

        searchInput.addEventListener('input', function() {
            const searchQuery = this.value.toLowerCase();
            const records = Array.from(recordsTable.children);
            records.forEach(record => {
                const text = record.textContent.toLowerCase();
                if (text.includes(searchQuery)) {
                    record.style.display = 'table-row';
                } else {
                    record.style.display = 'none';
                }
            });
        });

        fetch('../backend/طلاب.php')
            .then(response => response.json())
            .then(data => {
                data.forEach(record => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${record.اسم_الطالب}</td>
                        <td>${record.تاريخ_الميلاد}</td>
                        <td>
                            <a href="edit_طلاب.php?id=${record.id}" class="text-indigo-500 hover:text-indigo-700">تعديل</a>
                            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-2" onclick="deleteRecord(${record.id})">حذف</button>
                        </td>
                    `;
                    recordsTable.appendChild(row);
                });
            })
            .catch(error => console.error(error));

        function deleteRecord(id) {
            fetch(`../backend/طلاب.php?delete=${id}`, { method: 'DELETE' })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف الطالب بنجاح');
                        window.location.reload();
                    } else {
                        alert('حدث خطأ أثناء حذف الطالب');
                    }
                })
                .catch(error => console.error(error));
        }
    </script>
</body>
</html>

**backend/طلاب.php**

<?php
// Database connection
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get all records
$query = "SELECT * FROM طلاب";
$result = $conn->query($query);

// Get delete ID (if exists)
$deleteId = $_GET['delete'] ?? null;

// Process delete request
if ($deleteId) {
    $query = "DELETE FROM طلاب WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $deleteId);
    $stmt->execute();
    $stmt->close();
    echo json_encode(['success' => true]);
} else {
    // Get all records
    $records = array();
    while ($row = $result->fetch_assoc()) {
        $records[] = $row;
    }
    echo json_encode($records);
}

$conn->close();
?>