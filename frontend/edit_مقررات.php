**edit_مقررات.php**

<?php
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get the ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$existingRecord = json_decode(file_get_contents('../backend/مقررات.php?id=' . $id), true);

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل مقررات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-md shadow-md">
        <h2 class="text-slate-900 text-lg font-bold mb-4">تعديل مقررات</h2>
        <form id="edit-form" class="space-y-4">
            <div>
                <label for="name" class="text-slate-900 text-sm font-bold">اسم مقررات</label>
                <input type="text" id="name" name="name" class="w-full p-2 text-sm text-gray-700 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="<?= $existingRecord['name'] ?>">
            </div>
            <div>
                <label for="description" class="text-slate-900 text-sm font-bold">وصف مقررات</label>
                <textarea id="description" name="description" class="w-full p-2 text-sm text-gray-700 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" rows="5"><?= $existingRecord['description'] ?></textarea>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md">حفظ</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    type: 'PUT',
                    url: '../backend/مقررات.php',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'list_مقررات.php';
                        } else {
                            alert(response.message);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**مقررات.php (backend)**

<?php
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get the ID from URL
$id = $_GET['id'];

// Fetch existing record details from database
// Replace with your actual database query
$existingRecord = array(
    'id' => $id,
    'name' => 'مقررات',
    'description' => 'وصف مقررات'
);

// Return existing record details as JSON
echo json_encode($existingRecord);
?>