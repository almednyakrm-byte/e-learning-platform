**create_teachers.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
require_once 'header.php';
require_once 'nav.php';

// Include form
require_once 'create_teachers_form.php';

// Include footer
require_once 'footer.php';


**create_teachers_form.php**

<div class="max-w-md mx-auto p-8 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-slate-900 mb-4">Create Teacher</h2>
    <form id="create-teacher-form" class="space-y-4">
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="name">
                    Name
                </label>
                <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="name" type="text" placeholder="John Doe">
            </div>
            <div class="w-full md:w-1/2 px-3">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="email">
                    Email
                </label>
                <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="email" type="email" placeholder="john.doe@example.com">
            </div>
        </div>
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="phone">
                    Phone
                </label>
                <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="phone" type="tel" placeholder="123-456-7890">
            </div>
            <div class="w-full md:w-1/2 px-3">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="subject">
                    Subject
                </label>
                <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="subject" type="text" placeholder="Mathematics">
            </div>
        </div>
        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Create Teacher</button>
    </form>
</div>

<script>
    $(document).ready(function() {
        $('#create-teacher-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/teachers.php',
                data: formData,
                success: function(response) {
                    if (response == 'success') {
                        window.location.href = 'list_teachers.php';
                    } else {
                        alert('Error creating teacher');
                    }
                }
            });
        });
    });
</script>


**teachers.php (backend)**

<?php
// Connect to database
$conn = mysqli_connect('localhost', 'username', 'password', 'database');

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get form data
$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$subject = $_POST['subject'];

// Insert data into database
$sql = "INSERT INTO teachers (name, email, phone, subject) VALUES ('$name', '$email', '$phone', '$subject')";
if (mysqli_query($conn, $sql)) {
    echo 'success';
} else {
    echo 'Error creating teacher';
}

// Close connection
mysqli_close($conn);


Note: This is a basic example and you should consider security measures such as sanitizing user input and using prepared statements to prevent SQL injection.