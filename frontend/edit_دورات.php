**edit_دورات.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Fetch existing record details via AJAX
$js = "
    $(document).ready(function() {
        $.ajax({
            type: 'GET',
            url: '../backend/دورات.php?id=" . $id . "',
            success: function(data) {
                $('#name').val(data.name);
                $('#description').val(data.description);
                $('#price').val(data.price);
            }
        });
    });
";

// Include JavaScript code
echo "<script>$js</script>";

// Include Tailwind CSS and JavaScript
echo "<link href='https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css' rel='stylesheet'>";
echo "<script src='https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.js'></script>";

// Include form
?>

<div class="container mx-auto p-4">
    <h1 class="text-3xl font-bold text-slate-900 mb-4">Edit دورات</h1>
    <form id="edit-form" class="bg-white p-4 rounded shadow-md">
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-slate-900">Name:</label>
            <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-sm text-slate-900 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
        </div>
        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-slate-900">Description:</label>
            <textarea id="description" name="description" class="block w-full p-2 mt-1 text-sm text-slate-900 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"></textarea>
        </div>
        <div class="mb-4">
            <label for="price" class="block text-sm font-medium text-slate-900">Price:</label>
            <input type="number" id="price" name="price" class="block w-full p-2 mt-1 text-sm text-slate-900 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
        </div>
        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Save Changes</button>
    </form>
</div>

<?php
// Include JavaScript code for form submission
$js = "
    $(document).ready(function() {
        $('#edit-form').submit(function(event) {
            event.preventDefault();
            $.ajax({
                type: 'PUT',
                url: '../backend/دورات.php',
                data: $(this).serialize(),
                success: function() {
                    window.location.href = 'list_دورات.php';
                }
            });
        });
    });
";

// Include JavaScript code
echo "<script>$js</script>";
?>


**../backend/دورات.php**

<?php
// Check if id is set
if (!isset($_GET['id'])) {
    die('Invalid request');
}

// Connect to database
$conn = new PDO('mysql:host=localhost;dbname=database', 'username', 'password');

// Get id
$id = $_GET['id'];

// Fetch existing record details
$stmt = $conn->prepare('SELECT * FROM دورات WHERE id = :id');
$stmt->bindParam(':id', $id);
$stmt->execute();
$data = $stmt->fetch();

// Return data as JSON
header('Content-Type: application/json');
echo json_encode($data);

// Close connection
$conn = null;
?>