**edit_مدرسين.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Fetch existing record details
$url = '../backend/مدرسين.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Check if data exists
if (empty($data)) {
    echo 'Error: Record not found.';
    exit;
}

// Set form data
$form_data = [
    'name' => $data['name'],
    'email' => $data['email'],
    'phone' => $data['phone'],
];

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل مدرس</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-md shadow-md">
        <h2 class="text-slate-900 text-lg font-bold mb-4">تعديل مدرس</h2>
        <form id="edit-form" class="space-y-4">
            <div>
                <label for="name" class="text-slate-900 text-sm">اسم المدرس</label>
                <input type="text" id="name" name="name" class="w-full p-2 pl-10 text-sm text-gray-700 bg-gray-100 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" value="<?= $form_data['name'] ?>">
            </div>
            <div>
                <label for="email" class="text-slate-900 text-sm">البريد الإلكتروني</label>
                <input type="email" id="email" name="email" class="w-full p-2 pl-10 text-sm text-gray-700 bg-gray-100 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" value="<?= $form_data['email'] ?>">
            </div>
            <div>
                <label for="phone" class="text-slate-900 text-sm">رقم الهاتف</label>
                <input type="tel" id="phone" name="phone" class="w-full p-2 pl-10 text-sm text-gray-700 bg-gray-100 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" value="<?= $form_data['phone'] ?>">
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md">تعديل</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/مدرسين.php',
                    data: formData,
                    success: function(response) {
                        if (response === 'success') {
                            window.location.href = 'list_<?= $_SESSION['mod_slug'] ?>.php';
                        } else {
                            alert('Error: ' + response);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Error: ' + error);
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/مدرسين.php**

<?php
// Check if id is set
if (!isset($_GET['id'])) {
    echo 'Error: ID not found.';
    exit;
}

// Get id
$id = $_GET['id'];

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch existing record details
$query = "SELECT * FROM مدرسين WHERE id = '$id'";
$result = $conn->query($query);

// Check if record exists
if ($result->num_rows > 0) {
    // Fetch record details
    $row = $result->fetch_assoc();
    echo json_encode($row);
} else {
    echo 'Error: Record not found.';
}

// Close connection
$conn->close();
?>


**backend/edit.php**

<?php
// Check if id is set
if (!isset($_GET['id'])) {
    echo 'Error: ID not found.';
    exit;
}

// Get id
$id = $_GET['id'];

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get data from form
$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];

// Query to update record
$query = "UPDATE مدرسين SET name = '$name', email = '$email', phone = '$phone' WHERE id = '$id'";
$conn->query($query);

// Check if update was successful
if ($conn->affected_rows > 0) {
    echo 'success';
} else {
    echo 'Error: Update failed.';
}

// Close connection
$conn->close();
?>