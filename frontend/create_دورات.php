**create_دورات.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Include header
include 'header.php';

// Include navigation
include 'navigation.php';

?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:px-12 xl:px-24">
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6 lg:p-8 xl:p-12">
        <h2 class="text-slate-900 text-lg font-bold mb-4">إضافة دورة جديدة</h2>
        <form id="create-dorat-form" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block text-slate-900 text-sm font-bold mb-2">اسم الدورة</label>
                    <input type="text" id="name" name="name" class="block w-full p-2 pl-10 text-sm text-slate-900 bg-white rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                </div>
                <div>
                    <label for="description" class="block text-slate-900 text-sm font-bold mb-2">وصف الدورة</label>
                    <textarea id="description" name="description" class="block w-full p-2 pl-10 text-sm text-slate-900 bg-white rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required></textarea>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="start_date" class="block text-slate-900 text-sm font-bold mb-2">تاريخ بداية الدورة</label>
                    <input type="date" id="start_date" name="start_date" class="block w-full p-2 pl-10 text-sm text-slate-900 bg-white rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                </div>
                <div>
                    <label for="end_date" class="block text-slate-900 text-sm font-bold mb-2">تاريخ نهاية الدورة</label>
                    <input type="date" id="end_date" name="end_date" class="block w-full p-2 pl-10 text-sm text-slate-900 bg-white rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                </div>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">إضافة دورة جديدة</button>
        </form>
    </div>
</div>

<?php
// Include footer
include 'footer.php';
?>

<script>
    $(document).ready(function() {
        $('#create-dorat-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/دورات.php',
                data: $(this).serialize(),
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_دورات.php';
                    } else {
                        alert('Error adding new course');
                    }
                }
            });
        });
    });
</script>


**Note:** This code assumes you have jQuery and a backend PHP script (`دورات.php`) to handle the form submission. You'll need to create the backend script to handle the form data and insert it into your database.

**Backend Script (دورات.php)**

<?php
// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Connect to database
    $db = new PDO('mysql:host=localhost;dbname=your_database', 'your_username', 'your_password');

    // Prepare and execute query
    $stmt = $db->prepare('INSERT INTO دورات (name, description, start_date, end_date) VALUES (:name, :description, :start_date, :end_date)');
    $stmt->bindParam(':name', $_POST['name']);
    $stmt->bindParam(':description', $_POST['description']);
    $stmt->bindParam(':start_date', $_POST['start_date']);
    $stmt->bindParam(':end_date', $_POST['end_date']);
    $stmt->execute();

    // Close database connection
    $db = null;

    // Output success message
    echo 'success';
} else {
    // Output error message
    echo 'Error adding new course';
}
?>

This is a basic example and you should adjust it according to your needs and database schema.