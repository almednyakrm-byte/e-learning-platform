**edit_courses.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get course ID from URL
$id = $_GET['id'];

// Fetch course details via AJAX
$course = json_decode(file_get_contents('../backend/courses.php?id=' . $id), true);

// Check if course exists
if (!$course) {
    echo 'Course not found';
    exit;
}

// Set page title and mod slug
$page_title = 'Edit Course';
$mod_slug = 'courses';

// Include header
include 'header.php';
?>

<!-- Edit Course Form -->
<div class="max-w-md mx-auto p-8 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-bold text-slate-900 mb-4"><?= $page_title ?></h2>
    <form id="edit-course-form" class="space-y-4">
        <div>
            <label for="title" class="block text-sm font-medium text-slate-900">Title</label>
            <input type="text" id="title" name="title" class="block w-full p-2 pl-10 text-sm text-slate-900 placeholder-slate-400 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" placeholder="Course Title" value="<?= $course['title'] ?>">
        </div>
        <div>
            <label for="description" class="block text-sm font-medium text-slate-900">Description</label>
            <textarea id="description" name="description" class="block w-full p-2 pl-10 text-sm text-slate-900 placeholder-slate-400 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" placeholder="Course Description"><?= $course['description'] ?></textarea>
        </div>
        <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-indigo-500 border border-indigo-500 rounded-lg hover:bg-indigo-700 focus:ring-indigo-500">Update Course</button>
    </form>
</div>

<!-- JavaScript -->
<script>
    // Fetch course details via GET
    fetch('../backend/courses.php?id=' + <?= $id ?>)
        .then(response => response.json())
        .then(data => {
            // Populate form fields
            document.getElementById('title').value = data.title;
            document.getElementById('description').value = data.description;
        })
        .catch(error => console.error(error));

    // Submit form via AJAX PUT
    document.getElementById('edit-course-form').addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(this);
        fetch('../backend/courses.php', {
            method: 'PUT',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'list_' + '<?= $mod_slug ?>' + '.php';
                } else {
                    console.error(data.error);
                }
            })
            .catch(error => console.error(error));
    });
</script>

<!-- Include footer -->
<?php include 'footer.php'; ?>


**courses.php (backend)**

<?php
// Check if course ID is set
if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

// Get course ID
$id = $_GET['id'];

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get course details
$sql = "SELECT * FROM courses WHERE id = '$id'";
$result = $conn->query($sql);

// Check if course exists
if ($result->num_rows > 0) {
    // Fetch course details
    $course = $result->fetch_assoc();
    echo json_encode($course);
} else {
    echo 'Course not found';
}

// Close database connection
$conn->close();
?>


**list_courses.php (example)**

<?php
// Include header
include 'header.php';
?>

<!-- List Courses -->
<div class="max-w-md mx-auto p-8 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-bold text-slate-900 mb-4">Courses</h2>
    <ul>
        <!-- List courses here -->
    </ul>
</div>

<!-- Include footer -->
<?php include 'footer.php'; ?>