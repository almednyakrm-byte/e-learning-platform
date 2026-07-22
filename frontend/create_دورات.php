**create_دورات.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/db.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);
    $duration = trim($_POST['duration']);

    if (!empty($name) && !empty($description) && !empty($price) && !empty($duration)) {
        // Insert data into database
        $query = "INSERT INTO دورات (name, description, price, duration) VALUES (?, ?, ?, ?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("ssss", $name, $description, $price, $duration);
        $stmt->execute();

        // Redirect back to list page
        header('Location: list_دورات.php');
        exit;
    } else {
        $error = 'Please fill in all fields';
    }
}

// Include header
require_once '../includes/header.php';

?>

<!-- Create دورات form -->
<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-slate-900 text-lg font-bold mb-4">Create New دورات</h2>

    <?php if (isset($error)) : ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?= $error ?>
        </div>
    <?php endif; ?>

    <form id="create دورات form" method="post">
        <div class="mb-4">
            <label for="name" class="text-slate-900 block mb-2">Name:</label>
            <input type="text" id="name" name="name" class="w-full p-2 text-slate-900 border border-slate-300 rounded" required>
        </div>

        <div class="mb-4">
            <label for="description" class="text-slate-900 block mb-2">Description:</label>
            <textarea id="description" name="description" class="w-full p-2 text-slate-900 border border-slate-300 rounded" required></textarea>
        </div>

        <div class="mb-4">
            <label for="price" class="text-slate-900 block mb-2">Price:</label>
            <input type="number" id="price" name="price" class="w-full p-2 text-slate-900 border border-slate-300 rounded" required>
        </div>

        <div class="mb-4">
            <label for="duration" class="text-slate-900 block mb-2">Duration:</label>
            <input type="text" id="duration" name="duration" class="w-full p-2 text-slate-900 border border-slate-300 rounded" required>
        </div>

        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Create دورات</button>
    </form>
</div>

<!-- Include footer -->
<?php require_once '../includes/footer.php'; ?>

<script>
    $(document).ready(function() {
        $('#create دورات form').submit(function(e) {
            e.preventDefault();

            $.ajax({
                type: 'POST',
                url: '../backend/دورات.php',
                data: $(this).serialize(),
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_دورات.php';
                    } else {
                        console.error(response);
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr, status, error);
                }
            });
        });
    });
</script>

**Note:** This code assumes you have a `db.php` file that connects to your database and a `backend/دورات.php` file that handles the form submission. You'll need to modify the code to fit your specific database schema and backend logic. Additionally, this code uses jQuery for the AJAX request, so make sure you have it included in your project.