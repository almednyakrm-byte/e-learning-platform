**create_مدرسين.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/db.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    // Check for empty fields
    if (empty($name) || empty($email) || empty($phone) || empty($address)) {
        echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Error!</strong> Please fill in all fields.
            <span class="block sm:inline">All fields are required.</span>
        </div>';
    } else {
        // Insert data into database
        $sql = "INSERT INTO مدرسين (name, email, phone, address) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $name, $email, $phone, $address);
        $stmt->execute();
        $stmt->close();

        // Redirect back to list page
        header('Location: list_مدرسين.php');
        exit;
    }
}

// Include header
require_once '../includes/header.php';

?>

<!-- Create Teachers Form -->
<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-slate-900 mb-4">Create New Teacher</h2>
    <form id="create-teacher-form" method="post">
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-slate-900">Name:</label>
            <input type="text" id="name" name="name" class="block w-full px-4 py-2 text-sm text-gray-700 placeholder-gray-300 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="John Doe">
        </div>
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-slate-900">Email:</label>
            <input type="email" id="email" name="email" class="block w-full px-4 py-2 text-sm text-gray-700 placeholder-gray-300 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="john.doe@example.com">
        </div>
        <div class="mb-4">
            <label for="phone" class="block text-sm font-medium text-slate-900">Phone:</label>
            <input type="tel" id="phone" name="phone" class="block w-full px-4 py-2 text-sm text-gray-700 placeholder-gray-300 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="+1234567890">
        </div>
        <div class="mb-4">
            <label for="address" class="block text-sm font-medium text-slate-900">Address:</label>
            <textarea id="address" name="address" class="block w-full px-4 py-2 text-sm text-gray-700 placeholder-gray-300 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="123 Main St, Anytown, USA"></textarea>
        </div>
        <button type="submit" name="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Create Teacher</button>
    </form>
</div>

<!-- Include footer -->
<?php require_once '../includes/footer.php'; ?>

<script>
    // AJAX form submission
    document.getElementById('create-teacher-form').addEventListener('submit', function(event) {
        event.preventDefault();
        var formData = new FormData(this);
        fetch('../backend/مدرسين.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = 'list_مدرسين.php';
            } else {
                console.error(data.error);
            }
        })
        .catch(error => console.error('Error:', error));
    });
</script>


**مدرسين.php (backend)**

<?php
// Include database connection
require_once '../config/db.php';

// Check if form data has been submitted
if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['phone']) && isset($_POST['address'])) {
    // Insert data into database
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    $sql = "INSERT INTO مدرسين (name, email, phone, address) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $email, $phone, $address);
    $stmt->execute();
    $stmt->close();

    // Return success response
    echo json_encode(array('success' => true));
} else {
    // Return error response
    echo json_encode(array('success' => false, 'error' => 'Invalid form data'));
}
?>