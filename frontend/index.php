<?php
session_start();

// Check if user is authenticated
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
    <title>منصة التعليم الإلكتروني</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .glassmorphism {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 10px;
            padding: 20px;
        }
    </style>
</head>
<body class="bg-slate-900 text-white">
    <div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-24">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-3xl font-bold">منصة التعليم الإلكتروني</h1>
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='logout.php'">تسجيل خروج</button>
        </div>
        <div class="glassmorphism mb-4 p-4">
            <h2 class="text-2xl font-bold mb-2">مرحباً بكم</h2>
            <p>منصة التعليم الإلكتروني</p>
        </div>
        <div class="glassmorphism mb-4 p-4">
            <h2 class="text-2xl font-bold mb-2">إحصائيات</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="bg-slate-800 rounded p-4">
                    <h3 class="text-lg font-bold mb-2">طلاب</h3>
                    <p id="students-count"></p>
                </div>
                <div class="bg-slate-800 rounded p-4">
                    <h3 class="text-lg font-bold mb-2">دورات</h3>
                    <p id="courses-count"></p>
                </div>
                <div class="bg-slate-800 rounded p-4">
                    <h3 class="text-lg font-bold mb-2">مدرسين</h3>
                    <p id="teachers-count"></p>
                </div>
            </div>
        </div>
        <div class="glassmorphism mb-4 p-4">
            <h2 class="text-2xl font-bold mb-2">روابط سريعة</h2>
            <ul class="list-none mb-0">
                <li class="mb-2"><a href="#" class="text-white hover:text-indigo-500">طلاب</a></li>
                <li class="mb-2"><a href="#" class="text-white hover:text-indigo-500">دورات</a></li>
                <li class="mb-2"><a href="#" class="text-white hover:text-indigo-500">مدرسين</a></li>
            </ul>
        </div>
    </div>

    <script>
        fetch('/api/stats')
            .then(response => response.json())
            .then(data => {
                document.getElementById('students-count').innerText = data.students;
                document.getElementById('courses-count').innerText = data.courses;
                document.getElementById('teachers-count').innerText = data.teachers;
            })
            .catch(error => console.error(error));
    </script>
</body>
</html>


You will need to create a backend API to fetch the stats data. For example, you can create a PHP file `api/stats.php`:


<?php
header('Content-Type: application/json');

// Replace with your database connection
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch stats data
$stmt = $conn->prepare("SELECT COUNT(*) as students, COUNT(*) as courses, COUNT(*) as teachers FROM students, courses, teachers");
$stmt->execute();
$stmt->bind_result($students, $courses, $teachers);
$stmt->fetch();
$stmt->close();

// Close connection
$conn->close();

// Output stats data
echo json_encode([
    'students' => $students,
    'courses' => $courses,
    'teachers' => $teachers
]);
?>


Make sure to replace the database connection details with your actual database credentials.