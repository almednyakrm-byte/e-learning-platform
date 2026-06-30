**edit_teachers.php**

<?php
// Session validation
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get teacher ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$teacher = json_decode(file_get_contents('../backend/teachers.php?id=' . $id), true);

// Check if teacher exists
if (empty($teacher)) {
    echo 'Teacher not found!';
    exit;
}

// Set page title and mod slug
$page_title = 'Edit Teacher';
$mod_slug = 'teachers';

// Include header and navigation
include 'header.php';
include 'navigation.php';
?>

<!-- Page content -->
<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-12">
    <h1 class="text-3xl font-bold text-slate-900 mb-4"><?= $page_title ?></h1>

    <!-- Form -->
    <form id="edit-teacher-form" class="bg-white rounded shadow-md p-4">
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-slate-900">Name:</label>
            <input type="text" id="name" name="name" class="block w-full p-2 text-sm text-gray-900 rounded" value="<?= $teacher['name'] ?>">
        </div>
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-slate-900">Email:</label>
            <input type="email" id="email" name="email" class="block w-full p-2 text-sm text-gray-900 rounded" value="<?= $teacher['email'] ?>">
        </div>
        <div class="mb-4">
            <label for="phone" class="block text-sm font-medium text-slate-900">Phone:</label>
            <input type="tel" id="phone" name="phone" class="block w-full p-2 text-sm text-gray-900 rounded" value="<?= $teacher['phone'] ?>">
        </div>
        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Update Teacher</button>
    </form>
</div>

<!-- JavaScript -->
<script>
    // Fetch existing record details via GET
    fetch('../backend/teachers.php?id=' + <?= $id ?>)
        .then(response => response.json())
        .then(data => {
            // Populate form fields
            document.getElementById('name').value = data.name;
            document.getElementById('email').value = data.email;
            document.getElementById('phone').value = data.phone;
        })
        .catch(error => console.error(error));

    // Submit form via AJAX PUT request
    document.getElementById('edit-teacher-form').addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(this);
        fetch('../backend/teachers.php', {
            method: 'PUT',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'list_' + <?= $mod_slug ?> + '.php';
                } else {
                    console.error(data.error);
                }
            })
            .catch(error => console.error(error));
    });
</script>

<!-- Include footer -->
<?php include 'footer.php'; ?>


**teachers.php (backend)**

<?php
// Check if teacher ID is set
if (!isset($_GET['id'])) {
    http_response_code(400);
    exit;
}

// Get teacher ID
$id = $_GET['id'];

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get teacher details
$stmt = $conn->prepare("SELECT * FROM teachers WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch teacher details
$teacher = $result->fetch_assoc();

// Close database connection
$conn->close();

// Output teacher details as JSON
echo json_encode($teacher);
?>


**list_teachers.php (example)**

<?php
// Include header and navigation
include 'header.php';
include 'navigation.php';
?>

<!-- Page content -->
<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-12">
    <h1 class="text-3xl font-bold text-slate-900 mb-4">Teachers List</h1>

    <!-- Table -->
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700">
            <tr>
                <th scope="col" class="py-3 px-6">Name</th>
                <th scope="col" class="py-3 px-6">Email</th>
                <th scope="col" class="py-3 px-6">Phone</th>
                <th scope="col" class="py-3 px-6">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Connect to database
            $conn = new mysqli('localhost', 'username', 'password', 'database');

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Get teachers list
            $stmt = $conn->prepare("SELECT * FROM teachers");
            $stmt->execute();
            $result = $stmt->get_result();

            // Fetch teachers list
            while ($teacher = $result->fetch_assoc()) {
                ?>
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                    <td class="py-4 px-6"><?= $teacher['name'] ?></td>
                    <td class="py-4 px-6"><?= $teacher['email'] ?></td>
                    <td class="py-4 px-6"><?= $teacher['phone'] ?></td>
                    <td class="py-4 px-6">
                        <a href="edit_teachers.php?id=<?= $teacher['id'] ?>" class="text-indigo-500 hover:text-indigo-700">Edit</a>
                    </td>
                </tr>
                <?php
            }

            // Close database connection
            $conn->close();
            ?>
        </tbody>
    </table>
</div>

<!-- Include footer -->
<?php include 'footer.php'; ?>