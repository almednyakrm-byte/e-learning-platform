<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-r from-slate-900 to-indigo-500 h-screen">
    <div class="flex justify-center items-center h-full">
        <div class="glassmorphic-card bg-white/10 backdrop-blur-md p-10 rounded-md shadow-2xl w-96">
            <h2 class="text-3xl text-center text-white mb-5">Login</h2>
            <form id="login-form" class="space-y-4">
                <div class="space-y-1">
                    <label for="username" class="block text-sm text-white">Username</label>
                    <input type="text" id="username" name="username" class="block w-full px-4 py-2 text-sm text-gray-700 placeholder:text-gray-400 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-indigo-500 focus:border-indigo-500" placeholder="Username" pattern="[A-Za-z\u0600-\u06FF0-9\s]+" required>
                    <div id="username-error" class="text-red-500 hidden"></div>
                </div>
                <div class="space-y-1">
                    <label for="password" class="block text-sm text-white">Password</label>
                    <input type="password" id="password" name="password" class="block w-full px-4 py-2 text-sm text-gray-700 placeholder:text-gray-400 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-indigo-500 focus:border-indigo-500" placeholder="Password" required>
                    <div id="password-error" class="text-red-500 hidden"></div>
                </div>
                <button type="submit" class="w-full px-4 py-2 text-sm text-white bg-indigo-500 hover:bg-indigo-600 rounded-md focus:outline-none focus:ring focus:ring-indigo-500 focus:border-indigo-500">Login</button>
            </form>
            <p class="text-center text-sm text-white mt-5">Don't have an account? <a href="register.php" class="text-indigo-500 hover:text-indigo-600">Register</a></p>
        </div>
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
                    document.getElementById('username-error').textContent = data.username_error;
                    document.getElementById('password-error').textContent = data.password_error;
                    document.getElementById('username-error').classList.remove('hidden');
                    document.getElementById('password-error').classList.remove('hidden');
                }
            } catch (error) {
                console.error(error);
                alert('Error logging in. Please try again.');
            }
        });
    </script>
</body>
</html>


This code creates a premium-looking login page with a glassmorphic layout, gradients, and a form for username and password input. It uses the Tailwind CSS CDN for styling and includes a beautiful glassmorphic layout with gradients. The form includes validation rules for username and password input using standard HTML input pattern validators. The AJAX JavaScript code uses the Fetch API to submit the credentials to the backend PHP script and handles the response or error alerts dynamically. The code also includes a direct link to the register.php page.