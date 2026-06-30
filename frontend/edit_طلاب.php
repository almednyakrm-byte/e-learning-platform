**edit_طلاب.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via AJAX
$existingRecord = json_decode(file_get_contents('../backend/طلاب.php?id=' . $id), true);

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل طالب</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .bg-slate-900 {
            background-color: #1A1D23;
        }
        .text-indigo-500 {
            color: #6b7280;
        }
    </style>
</head>
<body class="bg-slate-900 text-indigo-500">
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">تعديل طالب</h1>
        <form id="edit-form">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium mb-2">اسم الطالب</label>
                <input type="text" id="name" name="name" class="block w-full p-2 border border-gray-300 rounded-md" value="<?= $existingRecord['name'] ?>">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium mb-2">البريد الإلكتروني</label>
                <input type="email" id="email" name="email" class="block w-full p-2 border border-gray-300 rounded-md" value="<?= $existingRecord['email'] ?>">
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-sm font-medium mb-2">رقم الهاتف</label>
                <input type="tel" id="phone" name="phone" class="block w-full p-2 border border-gray-300 rounded-md" value="<?= $existingRecord['phone'] ?>">
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">تعديل</button>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            // Fetch existing record details via AJAX
            $.ajax({
                type: 'GET',
                url: '../backend/طلاب.php?id=' + <?= $id ?>,
                success: function(data) {
                    // Populate form fields
                    $('#name').val(data.name);
                    $('#email').val(data.email);
                    $('#phone').val(data.phone);
                }
            });

            // Handle form submission
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                // Send AJAX PUT request to update record
                $.ajax({
                    type: 'PUT',
                    url: '../backend/طلاب.php',
                    data: {
                        id: <?= $id ?>,
                        name: $('#name').val(),
                        email: $('#email').val(),
                        phone: $('#phone').val()
                    },
                    success: function() {
                        Swal.fire({
                            title: 'تم التعديل',
                            text: 'تم تعديل بيانات الطالب بنجاح',
                            icon: 'success'
                        }).then(function() {
                            window.location.href = 'list_طلاب.php';
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/طلاب.php**

<?php
// Check if ID is set
if (isset($_GET['id'])) {
    // Fetch existing record details
    $id = $_GET['id'];
    $record = array(
        'name' => 'John Doe',
        'email' => 'john.doe@example.com',
        'phone' => '0123456789'
    );
    echo json_encode($record);
} elseif (isset($_PUT['id'])) {
    // Update existing record
    $id = $_PUT['id'];
    $name = $_PUT['name'];
    $email = $_PUT['email'];
    $phone = $_PUT['phone'];
    // Update record in database
    // ...
    echo 'Record updated successfully';
}