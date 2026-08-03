**edit_فصول.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details
$data = json_decode(file_get_contents('../backend/فصول.php?id=' . $id), true);

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل فصل</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-md shadow-md">
        <h2 class="text-slate-900 text-lg font-bold mb-4">تعديل فصل</h2>
        <form id="edit-form">
            <div class="mb-4">
                <label for="title" class="block text-slate-900 text-sm font-bold mb-2">العنوان</label>
                <input type="text" id="title" name="title" class="w-full p-2 text-slate-900 border border-slate-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="<?= $data['title'] ?>">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-slate-900 text-sm font-bold mb-2">الوصف</label>
                <textarea id="description" name="description" class="w-full p-2 text-slate-900 border border-slate-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"><?= $data['description'] ?></textarea>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md">حفظ</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/فصول.php',
                    data: $(this).serialize() + '&id=' + <?= $id ?>,
                    success: function(response) {
                        if (response === 'success') {
                            window.location.href = 'list_<?= $_SESSION['mod_slug'] ?>.php';
                        } else {
                            alert('Error: ' + response);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/فصول.php**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    http_response_code(400);
    echo 'Error: ID is required';
    exit;
}

// Get ID
$id = $_GET['id'];

// Fetch existing record details
$data = array();
// Your database query to fetch the record details goes here
// For example:
// $data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM فصول WHERE id = '$id'"));

echo json_encode($data);