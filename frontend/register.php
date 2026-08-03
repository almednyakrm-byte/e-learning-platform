<!-- register.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 h-screen">
    <div class="flex justify-center items-center h-full">
        <div class="bg-white p-8 rounded-lg shadow-md w-96">
            <h2 class="text-2xl text-center text-slate-900 mb-4">Register</h2>
            <form id="register-form">
                <div class="mb-4">
                    <label for="username" class="block text-slate-900 text-sm font-bold mb-2">Username</label>
                    <input type="text" id="username" name="username" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" placeholder="Username" pattern="[A-Za-z\u0600-\u06FF0-9\s]+" required>
                    <p id="username-error" class="text-red-500 hidden"></p>
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-slate-900 text-sm font-bold mb-2">Email</label>
                    <input type="email" id="email" name="email" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" placeholder="Email" required>
                    <p id="email-error" class="text-red-500 hidden"></p>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-slate-900 text-sm font-bold mb-2">Password</label>
                    <input type="password" id="password" name="password" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" placeholder="Password" required>
                    <p id="password-error" class="text-red-500 hidden"></p>
                </div>
                <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Register</button>
            </form>
        </div>
    </div>

    <script>
        const form = document.getElementById('register-form');
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const username = document.getElementById('username');
            const email = document.getElementById('email');
            const password = document.getElementById('password');
            const usernameError = document.getElementById('username-error');
            const emailError = document.getElementById('email-error');
            const passwordError = document.getElementById('password-error');

            if (username.value.trim() === '') {
                usernameError.textContent = 'Username is required';
                usernameError.classList.remove('hidden');
                username.classList.add('border', 'border-red-500');
            } else if (!username.value.match(pattern)) {
                usernameError.textContent = 'Invalid username';
                usernameError.classList.remove('hidden');
                username.classList.add('border', 'border-red-500');
            } else {
                usernameError.classList.add('hidden');
                username.classList.remove('border', 'border-red-500');
            }

            if (email.value.trim() === '') {
                emailError.textContent = 'Email is required';
                emailError.classList.remove('hidden');
                email.classList.add('border', 'border-red-500');
            } else if (!email.value.match(/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/)) {
                emailError.textContent = 'Invalid email';
                emailError.classList.remove('hidden');
                email.classList.add('border', 'border-red-500');
            } else {
                emailError.classList.add('hidden');
                email.classList.remove('border', 'border-red-500');
            }

            if (password.value.trim() === '') {
                passwordError.textContent = 'Password is required';
                passwordError.classList.remove('hidden');
                password.classList.add('border', 'border-red-500');
            } else {
                passwordError.classList.add('hidden');
                password.classList.remove('border', 'border-red-500');
            }

            if (username.value.trim() !== '' && email.value.trim() !== '' && password.value.trim() !== '') {
                fetch('../backend/auth.php?action=register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        username: username.value,
                        email: email.value,
                        password: password.value
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Registration successful');
                        window.location.href = 'login.php';
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => console.error(error));
            }
        });
    </script>
</body>
</html>


Note: The `pattern` variable is not defined in the code. You need to define it before using it in the `pattern` attribute of the input fields. The `pattern` variable should match the pattern you want to validate the input against.


const pattern = "[A-Za-z\u0600-\u06FF0-9\s]+";