<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-image: linear-gradient(to bottom, #1a1d23, #2c2f36);
            background-size: 100% 300px;
            background-position: 0% 100%;
            transition: background-position 1s linear;
        }
        
        .glassmorphic {
            background: linear-gradient(90deg, #1a1d23, #2c2f36);
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
        
        .glassmorphic::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, #1a1d23, #2c2f36);
            mix-blend-mode: multiply;
            opacity: 0.5;
            border-radius: 10px;
        }
        
        .gradient {
            background: linear-gradient(to bottom, #1a1d23, #2c2f36);
            background-size: 100% 300px;
            background-position: 0% 100%;
            transition: background-position 1s linear;
        }
    </style>
</head>
<body class="h-screen bg-gray-100">
    <div class="flex justify-center items-center h-screen">
        <div class="glassmorphic p-10 bg-white rounded-lg shadow-md">
            <h2 class="text-3xl font-bold text-center mb-4">Login</h2>
            <form id="login-form">
                <div class="mb-4">
                    <label for="username" class="block text-gray-700 text-sm font-bold mb-2">Username</label>
                    <input type="text" id="username" name="username" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" pattern="[A-Za-z\u0600-\u06FF0-9\s]+" required>
                    <div id="username-error" class="text-red-500 hidden"></div>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                    <input type="password" id="password" name="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    <div id="password-error" class="text-red-500 hidden"></div>
                </div>
                <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Login</button>
                <p class="text-center text-gray-500 mt-4">Don't have an account? <a href="register.php" class="text-indigo-500 hover:text-indigo-700">Register</a></p>
            </form>
        </div>
    </div>
    
    <script>
        const form = document.getElementById('login-form');
        const usernameInput = document.getElementById('username');
        const passwordInput = document.getElementById('password');
        const usernameError = document.getElementById('username-error');
        const passwordError = document.getElementById('password-error');
        
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            usernameError.classList.remove('text-red-500');
            passwordError.classList.remove('text-red-500');
            usernameError.textContent = '';
            passwordError.textContent = '';
            
            const username = usernameInput.value.trim();
            const password = passwordInput.value.trim();
            
            if (!username || !password) {
                if (!username) {
                    usernameError.classList.add('text-red-500');
                    usernameError.textContent = 'Username is required';
                }
                if (!password) {
                    passwordError.classList.add('text-red-500');
                    passwordError.textContent = 'Password is required';
                }
                return;
            }
            
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
                    if (data.error.username) {
                        usernameError.classList.add('text-red-500');
                        usernameError.textContent = data.error.username;
                    }
                    if (data.error.password) {
                        passwordError.classList.add('text-red-500');
                        passwordError.textContent = data.error.password;
                    }
                }
            } catch (error) {
                console.error(error);
                alert('Error logging in. Please try again later.');
            }
        });
    </script>
</body>
</html>

This code uses Tailwind CSS to create a premium-looking login page with a glassmorphic layout and gradients. It includes a form for username and password input, with validation rules and error messages. The form is submitted using AJAX with the Fetch API, and the response is handled dynamically. The code also includes a link to the register page.