**create_دورات.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include header
include 'header.php';

// Include navigation
include 'navigation.php';

// Include form
include 'create_دورات_form.php';

// Include footer
include 'footer.php';


**create_دورات_form.php**

<div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
    <h2 class="text-2xl font-bold text-slate-900 mb-4">إضافة دورة جديدة</h2>
    <form id="create-dorat-form" class="space-y-6">
        <div class="grid grid-cols-1 gap-6">
            <div class="col-span-6 sm:col-span-3">
                <label for="name" class="block text-sm font-medium text-slate-900">اسم الدورة</label>
                <input type="text" id="name" name="name" class="block w-full px-3 py-2 pl-10 text-sm text-slate-900 placeholder:text-slate-400 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="اسم الدورة">
            </div>
            <div class="col-span-6 sm:col-span-3">
                <label for="description" class="block text-sm font-medium text-slate-900">وصف الدورة</label>
                <textarea id="description" name="description" class="block w-full px-3 py-2 pl-10 text-sm text-slate-900 placeholder:text-slate-400 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="وصف الدورة"></textarea>
            </div>
            <div class="col-span-6 sm:col-span-3">
                <label for="price" class="block text-sm font-medium text-slate-900">سعر الدورة</label>
                <input type="number" id="price" name="price" class="block w-full px-3 py-2 pl-10 text-sm text-slate-900 placeholder:text-slate-400 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="سعر الدورة">
            </div>
            <div class="col-span-6 sm:col-span-3">
                <label for="duration" class="block text-sm font-medium text-slate-900">مدة الدورة</label>
                <input type="number" id="duration" name="duration" class="block w-full px-3 py-2 pl-10 text-sm text-slate-900 placeholder:text-slate-400 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="مدة الدورة">
            </div>
        </div>
        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-500 hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">إضافة دورة جديدة</button>
    </form>
</div>

<script>
    $(document).ready(function() {
        $('#create-dorat-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/دورات.php',
                data: $(this).serialize(),
                success: function(response) {
                    if (response == 'success') {
                        window.location.href = 'list_دورات.php';
                    } else {
                        alert('Error: ' + response);
                    }
                }
            });
        });
    });
</script>


**backend/دورات.php**

<?php
// Check if form data is sent
if (isset($_POST['name']) && isset($_POST['description']) && isset($_POST['price']) && isset($_POST['duration'])) {
    // Connect to database
    $conn = new mysqli('localhost', 'username', 'password', 'database');
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Prepare query
    $stmt = $conn->prepare("INSERT INTO دورات (name, description, price, duration) VALUES (?, ?, ?, ?)");
    
    // Bind parameters
    $stmt->bind_param("ssss", $_POST['name'], $_POST['description'], $_POST['price'], $_POST['duration']);
    
    // Execute query
    $stmt->execute();
    
    // Close statement and connection
    $stmt->close();
    $conn->close();
    
    // Output success message
    echo 'success';
} else {
    // Output error message
    echo 'Error: Form data not sent';
}
?>