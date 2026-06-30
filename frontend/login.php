<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
    <style>
        body {
            background-image: linear-gradient(to bottom, #1a1d23, #2c2f36);
            background-size: 100% 300px;
            background-position: 0% 100%;
            transition: background-position 0.5s ease-in-out;
        }
        .glassmorphic {
            background-color: #1a1d23;
            background-image: linear-gradient(45deg, #1a1d23 25%, transparent 25%, transparent 75%, #1a1d23 75%, #1a1d23 100%),
                linear-gradient(135deg, #1a1d23 25%, transparent 25%, transparent 75%, #1a1d23 75%, #1a1d23 100%);
            background-size: 20px 20px;
            background-position: 0 0, 10px 10px;
            backdrop-filter: blur(5px);
            border-radius: 10px;
            padding: 20px;
        }
    </style>
</head>
<body class="h-screen flex justify-center items-center bg-gray-100">
    <div class="glassmorphic w-96 p-8 bg-slate-900 rounded-lg shadow-md">
        <h2 class="text-2xl text-indigo-500 font-bold mb-4">Login</h2>
        <form id="login-form">
            <div class="mb-4">
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <input type="text" id="username" name="username" class="block w-full p-2 mt-1 text-sm text-gray-700 bg-gray-100 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" pattern="[A-Za-z\u0600-\u06FF0-9\s]+" required>
                <div id="username-error" class="text-red-500 hidden"></div>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" id="password" name="password" class="block w-full p-2 mt-1 text-sm text-gray-700 bg-gray-100 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" required>
                <div id="password-error" class="text-red-500 hidden"></div>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Login</button>
            <p class="text-sm text-gray-700 mt-2">Don't have an account? <a href="register.php" class="text-indigo-500 hover:text-indigo-700">Register</a></p>
        </form>
    </div>

    <script>
        const form = document.getElementById('login-form');
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            try {
                const response = await fetch('../backend/auth.php?action=login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ username, password })
                });
                const data = await response.json();
                if (data.success) {
                    window.location.href = 'dashboard.php';
                } else {
                    alert(data.message);
                }
            } catch (error) {
                alert('Error: ' + error.message);
            }
        });
    </script>
</body>
</html>


This code creates a premium-looking login page with a glassmorphic layout, gradients, and a form for username and password input. It uses the Tailwind CSS CDN for styling and includes a beautiful glassmorphic layout with gradients. The form includes validation rules and uses the standard HTML input pattern validator to support Arabic and Latin characters. The AJAX JavaScript code uses the fetch API to submit the credentials to the backend and handle the response or error alerts dynamically. The direct link to the register page is also included.