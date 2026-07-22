**edit_طلاب.php**

<?php
// Session validation
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/طلاب.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Check if data is available
if ($data) {
    // Extract data
    $name = $data['name'];
    $email = $data['email'];
    $phone = $data['phone'];
    $address = $data['address'];
} else {
    echo "Error: No data found.";
    exit;
}

// Include header
include_once 'header.php';

?>

<!-- Main content -->
<main class="max-w-7xl mx-auto p-4">
    <h1 class="text-3xl font-bold mb-4">Edit Student</h1>

    <!-- Form -->
    <form id="edit-form" class="bg-white p-4 rounded shadow-md">
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-slate-900">Name:</label>
            <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-sm text-slate-900 border border-slate-300 rounded-md" value="<?= $name ?>">
        </div>

        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-slate-900">Email:</label>
            <input type="email" id="email" name="email" class="block w-full p-2 mt-1 text-sm text-slate-900 border border-slate-300 rounded-md" value="<?= $email ?>">
        </div>

        <div class="mb-4">
            <label for="phone" class="block text-sm font-medium text-slate-900">Phone:</label>
            <input type="tel" id="phone" name="phone" class="block w-full p-2 mt-1 text-sm text-slate-900 border border-slate-300 rounded-md" value="<?= $phone ?>">
        </div>

        <div class="mb-4">
            <label for="address" class="block text-sm font-medium text-slate-900">Address:</label>
            <textarea id="address" name="address" class="block w-full p-2 mt-1 text-sm text-slate-900 border border-slate-300 rounded-md"><?= $address ?></textarea>
        </div>

        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Update</button>
    </form>
</main>

<!-- JavaScript -->
<script>
    // Fetch existing record details via GET
    fetch('../backend/طلاب.php?id=<?= $id ?>')
        .then(response => response.json())
        .then(data => {
            // Populate form fields
            document.getElementById('name').value = data.name;
            document.getElementById('email').value = data.email;
            document.getElementById('phone').value = data.phone;
            document.getElementById('address').value = data.address;
        })
        .catch(error => console.error(error));

    // Submit form via AJAX PUT request
    document.getElementById('edit-form').addEventListener('submit', event => {
        event.preventDefault();
        const formData = new FormData(event.target);
        fetch('../backend/طلاب.php', {
            method: 'PUT',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'list_طلاب.php';
                } else {
                    console.error(data.message);
                }
            })
            .catch(error => console.error(error));
    });
</script>

<?php
// Include footer
include_once 'footer.php';
?>


**backend/طلاب.php**

<?php
// Check if ID is provided
if (isset($_GET['id'])) {
    // Connect to database
    $db = new PDO('mysql:host=localhost;dbname=database_name', 'username', 'password');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch existing record details
    $stmt = $db->prepare('SELECT * FROM طلاب WHERE id = :id');
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->execute();
    $data = $stmt->fetch();

    // Return data as JSON
    echo json_encode($data);
} elseif (isset($_POST['id'])) {
    // Update existing record details
    $db = new PDO('mysql:host=localhost;dbname=database_name', 'username', 'password');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare and execute UPDATE query
    $stmt = $db->prepare('UPDATE طلاب SET name = :name, email = :email, phone = :phone, address = :address WHERE id = :id');
    $stmt->bindParam(':id', $_POST['id']);
    $stmt->bindParam(':name', $_POST['name']);
    $stmt->bindParam(':email', $_POST['email']);
    $stmt->bindParam(':phone', $_POST['phone']);
    $stmt->bindParam(':address', $_POST['address']);
    $stmt->execute();

    // Return success message as JSON
    echo json_encode(['success' => true]);
} else {
    // Return error message as JSON
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>