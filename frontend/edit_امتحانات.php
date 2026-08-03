**edit_امتحانات.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$existingRecord = json_decode(file_get_contents('../backend/امتحانات.php?id=' . $id), true);

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل امتحان</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-md shadow-md">
        <h2 class="text-slate-900 text-lg font-bold mb-4">تعديل امتحان</h2>
        <form id="edit-form" class="space-y-4">
            <div>
                <label for="title" class="text-slate-900 text-sm font-bold">العنوان</label>
                <input type="text" id="title" name="title" class="w-full p-2 text-sm text-gray-600 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="<?= $existingRecord['title'] ?>">
            </div>
            <div>
                <label for="description" class="text-slate-900 text-sm font-bold">الوصف</label>
                <textarea id="description" name="description" class="w-full p-2 text-sm text-gray-600 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"><?= $existingRecord['description'] ?></textarea>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md">تعديل</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/امتحانات.php',
                    data: $(this).serialize() + '&id=' + <?= $id ?>,
                    success: function(response) {
                        window.location.href = 'list_<?= $_SESSION['mod_slug'] ?>.php';
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/امتحانات.php**

<?php
// Check if id is set
if (!isset($_GET['id'])) {
    http_response_code(404);
    exit;
}

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get existing record details
$stmt = $conn->prepare("SELECT * FROM امتحانات WHERE id = ?");
$stmt->bind_param("i", $_GET['id']);
$stmt->execute();
$result = $stmt->get_result();

// Fetch existing record details
$existingRecord = $result->fetch_assoc();

// Close connection
$conn->close();

// Output existing record details as JSON
echo json_encode($existingRecord);
?>