**list_مواد.php**

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
    <title>مواد</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #1f2937;
            padding: 1rem;
            text-align: center;
        }
        .header .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: #ffffff;
        }
        .header .nav-links {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header .nav-links li {
            margin-right: 20px;
        }
        .header .nav-links a {
            color: #ffffff;
            text-decoration: none;
        }
        .header .nav-links a:hover {
            color: #ffffff;
            text-decoration: underline;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .table th {
            background-color: #1f2937;
            color: #ffffff;
        }
        .table td {
            background-color: #f7f7f7;
        }
        .table td a {
            text-decoration: none;
            color: #1f2937;
        }
        .table td a:hover {
            text-decoration: underline;
        }
        .search-bar {
            width: 50%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }
        .search-bar:focus {
            outline: none;
            border-color: #1f2937;
        }
        .add-new-item {
            background-color: #1f2937;
            color: #ffffff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .add-new-item:hover {
            background-color: #1f2937;
            color: #ffffff;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="logo">مواد</h1>
        <ul class="nav-links">
            <li><a href="index.php">الرئيسية</a></li>
            <li><a href="#">حسناً <?= $_SESSION['username'] ?></a></li>
            <li><a href="logout.php">تسجيل الخروج</a></li>
        </ul>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">قائمة المواد</h1>
        <div class="flex justify-between mb-4">
            <input type="search" class="search-bar" placeholder="بحث" id="search-input">
            <button class="add-new-item" onclick="location.href='create_مواد.php'">إضافة جديد</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>اسم المادة</th>
                    <th>وصف المادة</th>
                    <th>حذف</th>
                    <th>تعديل</th>
                </tr>
            </thead>
            <tbody id="table-body">
                <?php
                // Fetch data from backend
                $response = file_get_contents('../backend/مواد.php');
                $data = json_decode($response, true);
                foreach ($data as $item) {
                    echo '<tr>';
                    echo '<td>' . $item['name'] . '</td>';
                    echo '<td>' . $item['description'] . '</td>';
                    echo '<td><button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteItem(' . $item['id'] . ')">حذف</button></td>';
                    echo '<td><a href="edit_مواد.php?id=' . $item['id'] . '">تعديل</a></td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        // Search bar filtering
        const searchInput = document.getElementById('search-input');
        searchInput.addEventListener('input', function() {
            const searchValue = this.value.toLowerCase();
            const tableBody = document.getElementById('table-body');
            const rows = tableBody.getElementsByTagName('tr');
            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const cells = row.getElementsByTagName('td');
                let match = false;
                for (let j = 0; j < cells.length; j++) {
                    const cell = cells[j];
                    const text = cell.textContent.toLowerCase();
                    if (text.includes(searchValue)) {
                        match = true;
                        break;
                    }
                }
                if (match) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });

        // Delete item
        function deleteItem(id) {
            fetch('../backend/مواد.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('تم حذف المادة بنجاح');
                    location.reload();
                } else {
                    alert('حدث خطأ');
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</body>
</html>

This code includes a premium Tailwind UI design with a specific color palette matching the theme. It also includes session validation, a search bar filtering elements in real-time, and AJAX calls to fetch and delete items from the backend.