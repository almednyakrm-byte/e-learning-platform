<?php
session_start();
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
    <div class="container mx-auto p-4 pt-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-3xl font-bold">منصة التعليم الإلكتروني</h1>
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='logout.php'">تسجيل خروج</button>
        </div>
        <div class="glassmorphism bg-slate-900 p-4 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold mb-4">مرحباً <?php echo $_SESSION['username']; ?></h2>
            <div class="flex flex-wrap justify-center mb-4">
                <div class="w-full md:w-1/3 xl:w-1/3 p-6 text-center bg-white rounded-lg shadow-md">
                    <h3 class="text-lg font-bold mb-2">إحصائيات</h3>
                    <div id="stats-grid" class="flex flex-wrap justify-center"></div>
                </div>
            </div>
            <div class="flex flex-wrap justify-center mb-4">
                <div class="w-full md:w-1/3 xl:w-1/3 p-6 text-center bg-white rounded-lg shadow-md">
                    <h3 class="text-lg font-bold mb-2">روابط سريعة</h3>
                    <ul class="list-none mb-0">
                        <li class="mb-2">
                            <a href="students.php" class="text-lg font-bold hover:text-indigo-500">طلاب</a>
                        </li>
                        <li class="mb-2">
                            <a href="teachers.php" class="text-lg font-bold hover:text-indigo-500">معلمين</a>
                        </li>
                        <li class="mb-2">
                            <a href="courses.php" class="text-lg font-bold hover:text-indigo-500">مقررات</a>
                        </li>
                        <li class="mb-2">
                            <a href="grades.php" class="text-lg font-bold hover:text-indigo-500">درجات</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        fetch('/api/stats')
            .then(response => response.json())
            .then(data => {
                const statsGrid = document.getElementById('stats-grid');
                data.forEach(stat => {
                    const statElement = document.createElement('div');
                    statElement.classList.add('w-full', 'md:w-1/3', 'xl:w-1/3', 'p-6', 'text-center', 'bg-white', 'rounded-lg', 'shadow-md');
                    statElement.innerHTML = `
                        <h3 class="text-lg font-bold mb-2">${stat.title}</h3>
                        <p class="text-lg font-bold mb-2">${stat.value}</p>
                    `;
                    statsGrid.appendChild(statElement);
                });
            })
            .catch(error => console.error(error));
    </script>
</body>
</html>


This code assumes you have a backend API that returns stats data in JSON format. The API endpoint is assumed to be `/api/stats`. You'll need to replace this with your actual API endpoint.

The code uses Tailwind CSS for styling and Glassmorphism effect. The color palette is set to slate-900 and indigo-500 as per your requirements.

The dashboard layout includes a welcome message, logout button, overview stats grid, and quick links to manage modules. The stats grid is populated dynamically via an API call to `/api/stats`.

Note that this code assumes you have a `logout.php` file that handles user logout functionality. You'll need to create this file and implement the necessary logic.

Also, make sure to update the API endpoint and any other hardcoded values to match your actual project setup.