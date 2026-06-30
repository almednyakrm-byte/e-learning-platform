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
    <div class="container mx-auto p-4 h-full">
        <div class="max-w-md mx-auto p-8 bg-white rounded-lg shadow-lg">
            <h2 class="text-3xl font-bold text-indigo-500 mb-4">Register</h2>
            <form id="register-form">
                <div class="mb-4">
                    <label for="username" class="block text-gray-700 text-sm font-bold mb-2">Username</label>
                    <input type="text" id="username" name="username" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" pattern="[A-Za-z\u0600-\u06FF0-9\s]+" required>
                    <p id="username-error" class="text-red-500 hidden"></p>
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                    <input type="email" id="email" name="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    <p id="email-error" class="text-red-500 hidden"></p>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                    <input type="password" id="password" name="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" pattern="[A-Za-z\u0600-\u06FF0-9\s]+" required>
                    <p id="password-error" class="text-red-500 hidden"></p>
                </div>
                <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Register</button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            $('#register-form').submit(function(e) {
                e.preventDefault();
                var username = $('#username').val();
                var email = $('#email').val();
                var password = $('#password').val();

                if (username == '') {
                    $('#username-error').text('Username is required').removeClass('hidden').addClass('block');
                    return false;
                } else if (!username.match(pattern)) {
                    $('#username-error').text('Invalid username').removeClass('hidden').addClass('block');
                    return false;
                } else {
                    $('#username-error').text('').addClass('hidden').removeClass('block');
                }

                if (email == '') {
                    $('#email-error').text('Email is required').removeClass('hidden').addClass('block');
                    return false;
                } else if (!email.match(/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/)) {
                    $('#email-error').text('Invalid email').removeClass('hidden').addClass('block');
                    return false;
                } else {
                    $('#email-error').text('').addClass('hidden').removeClass('block');
                }

                if (password == '') {
                    $('#password-error').text('Password is required').removeClass('hidden').addClass('block');
                    return false;
                } else if (!password.match(pattern)) {
                    $('#password-error').text('Invalid password').removeClass('hidden').addClass('block');
                    return false;
                } else {
                    $('#password-error').text('').addClass('hidden').removeClass('block');
                }

                $.ajax({
                    type: 'POST',
                    url: '../backend/auth.php?action=register',
                    data: {
                        username: username,
                        email: email,
                        password: password
                    },
                    success: function(response) {
                        if (response == 'success') {
                            alert('Registration successful');
                            window.location.href = 'login.php';
                        } else {
                            alert('Registration failed');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


This code uses Tailwind CSS to create a premium-looking registration form. It includes validation rules for the username, email, and password fields. The form data is submitted via AJAX to the `auth.php` script, which handles the registration process. If the registration is successful, the user is redirected to the login page.