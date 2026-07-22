**edit_دورات.php**

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

// Fetch existing record details
$existingRecord = json_decode(file_get_contents('../backend/دورات.php?id=' . $id), true);

// Check if record exists
if (empty($existingRecord)) {
    echo 'Record not found';
    exit;
}

// Set page title and mod slug
$pageTitle = 'Edit دورات';
$modSlug = 'دورات';

// Include header
include '../includes/header.php';

?>

<!-- Main content -->
<main class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
    <h1 class="text-3xl font-bold leading-tight text-slate-900 mb-4"><?= $pageTitle ?></h1>

    <!-- Form -->
    <form id="edit-form" class="bg-white rounded-lg shadow-md p-4">
        <div class="grid grid-cols-1 gap-4 mb-4">
            <label for="name" class="block text-sm font-medium text-slate-700">Name</label>
            <input type="text" id="name" name="name" class="block w-full p-2 pl-10 text-sm text-slate-700 bg-slate-100 rounded-lg border border-slate-300 focus:ring-indigo-500 focus:border-indigo-500" value="<?= $existingRecord['name'] ?>">
        </div>
        <div class="grid grid-cols-1 gap-4 mb-4">
            <label for="description" class="block text-sm font-medium text-slate-700">Description</label>
            <textarea id="description" name="description" class="block w-full p-2 pl-10 text-sm text-slate-700 bg-slate-100 rounded-lg border border-slate-300 focus:ring-indigo-500 focus:border-indigo-500" rows="4"><?= $existingRecord['description'] ?></textarea>
        </div>
        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">Update</button>
    </form>
</main>

<!-- JavaScript -->
<script>
    // Fetch existing record details via GET
    fetch('../backend/دورات.php?id=' + <?= $id ?>)
        .then(response => response.json())
        .then(data => {
            // Populate form fields
            document.getElementById('name').value = data.name;
            document.getElementById('description').value = data.description;
        })
        .catch(error => console.error(error));

    // Handle form submission
    document.getElementById('edit-form').addEventListener('submit', event => {
        event.preventDefault();

        // Send AJAX PUT request
        fetch('../backend/دورات.php', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id: <?= $id ?>,
                name: document.getElementById('name').value,
                description: document.getElementById('description').value
            })
        })
            .then(response => response.json())
            .then(data => {
                // Redirect to list page
                window.location.href = 'list_<?= $modSlug ?>.php';
            })
            .catch(error => console.error(error));
    });
</script>

<?php
// Include footer
include '../includes/footer.php';
?>


**backend/دورات.php**

<?php
// Check if id is set
if (!isset($_GET['id'])) {
    echo 'Invalid request';
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
$query = "SELECT * FROM دورات WHERE id = '$id'";
$result = $conn->query($query);

// Check if record exists
if ($result->num_rows > 0) {
    // Fetch record details
    $row = $result->fetch_assoc();
    echo json_encode($row);
} else {
    echo 'Record not found';
}

// Close connection
$conn->close();
?>