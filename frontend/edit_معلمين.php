**edit_معلمين.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get the ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/معلمين.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Check if data exists
if (empty($data)) {
    echo 'Error: Record not found.';
    exit;
}

// Set page title and mod slug
$page_title = 'Edit معلمين';
$mod_slug = 'معلمين';

// Include header and footer
include 'header.php';
?>

<main class="max-w-7xl mx-auto p-4">
    <h1 class="text-3xl font-bold text-slate-900 mb-4"><?= $page_title ?></h1>
    <form id="edit-form" class="bg-white p-4 rounded-md shadow-md">
        <div class="grid grid-cols-1 gap-4 mb-4">
            <label for="name" class="block text-sm font-medium text-slate-900">Name:</label>
            <input type="text" id="name" name="name" class="block w-full p-2 text-sm text-gray-900 border border-gray-300 rounded-md" value="<?= $data['name'] ?>">
        </div>
        <div class="grid grid-cols-1 gap-4 mb-4">
            <label for="email" class="block text-sm font-medium text-slate-900">Email:</label>
            <input type="email" id="email" name="email" class="block w-full p-2 text-sm text-gray-900 border border-gray-300 rounded-md" value="<?= $data['email'] ?>">
        </div>
        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md">Update</button>
    </form>
</main>

<script>
    // Populate form fields
    const form = document.getElementById('edit-form');
    form.name.value = '<?= $data['name'] ?>';
    form.email.value = '<?= $data['email'] ?>';

    // AJAX PUT request on form submit
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        const formData = new FormData(form);
        fetch('../backend/معلمين.php', {
            method: 'PUT',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = 'list_<?= $mod_slug ?>.php';
            } else {
                console.error(data.error);
            }
        })
        .catch(error => console.error(error));
    });
</script>

<?php
include 'footer.php';
?>


**backend/معلمين.php**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    echo json_encode(['error' => 'ID not set']);
    exit;
}

// Get the ID
$id = $_GET['id'];

// Check if ID is numeric
if (!is_numeric($id)) {
    echo json_encode(['error' => 'Invalid ID']);
    exit;
}

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    echo json_encode(['error' => 'Connection failed']);
    exit;
}

// Get existing record details
$sql = "SELECT * FROM معلمين WHERE id = '$id'";
$result = $conn->query($sql);

// Check if record exists
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode($row);
} else {
    echo json_encode(['error' => 'Record not found']);
}

// Close connection
$conn->close();
?>


**Note:** This code assumes you have a `header.php` and `footer.php` file that includes the HTML header and footer respectively. You'll need to replace the placeholders with your actual database credentials and table name. Additionally, this code uses a simple AJAX request to update the record, you may want to consider using a more secure method such as CSRF tokens to prevent cross-site request forgery attacks.