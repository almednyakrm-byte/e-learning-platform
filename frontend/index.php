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
    <title>منصة تعليم إلكترونية شاملة</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }
        .glassmorphism-card {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="flex justify-between items-center p-4 bg-slate-900 text-white">
        <h1 class="text-3xl font-bold">منصة تعليم إلكترونية شاملة</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='logout.php'">تسجيل خروج</button>
    </div>
    <div class="flex justify-center items-center p-4 bg-slate-900 text-white">
        <h1 class="text-2xl font-bold">مرحباً بكم في منصة تعليم إلكترونية شاملة</h1>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 p-4">
        <?php
        // Fetch stats dynamically via Javascript API calls from the backend files
        $stats = json_decode(file_get_contents('https://example.com/api/stats'), true);
        ?>
        <div class="glassmorphism-card bg-white text-slate-900 p-4">
            <h2 class="text-lg font-bold">إجمالي الدورات</h2>
            <p class="text-3xl font-bold"><?= $stats['courses_count'] ?></p>
        </div>
        <div class="glassmorphism-card bg-white text-slate-900 p-4">
            <h2 class="text-lg font-bold">إجمالي الطلاب</h2>
            <p class="text-3xl font-bold"><?= $stats['students_count'] ?></p>
        </div>
        <div class="glassmorphism-card bg-white text-slate-900 p-4">
            <h2 class="text-lg font-bold">إجمالي الأساتذة</h2>
            <p class="text-3xl font-bold"><?= $stats['teachers_count'] ?></p>
        </div>
        <div class="glassmorphism-card bg-white text-slate-900 p-4">
            <h2 class="text-lg font-bold">إجمالي الامتحانات</h2>
            <p class="text-3xl font-bold"><?= $stats['exams_count'] ?></p>
        </div>
    </div>
    <div class="flex justify-center items-center p-4 bg-slate-900 text-white">
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='courses.php'">إدارة الدورات</button>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='students.php'">إدارة الطلاب</button>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='teachers.php'">إدارة الأساتذة</button>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='exams.php'">إدارة الامتحانات</button>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/axios@0.21.1/dist/axios.min.js"></script>
    <script>
        axios.get('https://example.com/api/stats')
            .then(response => {
                const stats = response.data;
                document.querySelector('.stats-grid .course-count').textContent = stats.courses_count;
                document.querySelector('.stats-grid .student-count').textContent = stats.students_count;
                document.querySelector('.stats-grid .teacher-count').textContent = stats.teachers_count;
                document.querySelector('.stats-grid .exam-count').textContent = stats.exams_count;
            })
            .catch(error => {
                console.error(error);
            });
    </script>
</body>
</html>


This code uses Tailwind CSS to create a premium-looking dashboard with a glassmorphism card layout. It also includes a session check to redirect the user to the login page if they are not authenticated. The stats are fetched dynamically via a JavaScript API call to the backend files.

Please note that you need to replace `https://example.com/api/stats` with the actual URL of your API endpoint that returns the stats data.

Also, make sure to create the necessary backend files (e.g. `api/stats.php`) to handle the API requests and return the stats data in JSON format.