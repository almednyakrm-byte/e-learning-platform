**create_طلاب.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
include 'header.php';
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-12 2xl:p-12">
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6 lg:p-8 xl:p-8 2xl:p-8">
        <h2 class="text-slate-900 font-bold text-lg mb-4">إضافة طالب جديد</h2>
        <form id="create-student-form">
            <div class="mb-4">
                <label for="name" class="text-slate-900 font-bold text-sm">اسم الطالب</label>
                <input type="text" id="name" name="name" class="w-full p-2 text-sm text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500" required>
            </div>
            <div class="mb-4">
                <label for="email" class="text-slate-900 font-bold text-sm">بريد إلكتروني</label>
                <input type="email" id="email" name="email" class="w-full p-2 text-sm text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500" required>
            </div>
            <div class="mb-4">
                <label for="phone" class="text-slate-900 font-bold text-sm">رقم الهاتف</label>
                <input type="tel" id="phone" name="phone" class="w-full p-2 text-sm text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500" required>
            </div>
            <div class="mb-4">
                <label for="address" class="text-slate-900 font-bold text-sm">عنوان</label>
                <textarea id="address" name="address" class="w-full p-2 text-sm text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500" required></textarea>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">إضافة</button>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#create-student-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/طلاب.php',
                data: formData,
                success: function(response) {
                    if (response == 'success') {
                        window.location.href = 'list_طلاب.php';
                    } else {
                        alert('Error: ' + response);
                    }
                }
            });
        });
    });
</script>

<?php
// Include footer
include 'footer.php';
?>


**طلاب.php (backend)**

<?php
// Include database connection
include 'db.php';

// Check if form data is submitted
if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['phone']) && isset($_POST['address'])) {
    // Prepare SQL query
    $sql = "INSERT INTO طلاب (name, email, phone, address) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $_POST['name'], $_POST['email'], $_POST['phone'], $_POST['address']);
    // Execute query
    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'Error: ' . $stmt->error;
    }
    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>