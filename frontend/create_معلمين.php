<?php
// Start session
session_start();

// Session validation
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
include '../backend/db.php';

// Get module slug
$mod_slug = 'معلمين';

// Get module name
$mod_name = 'معلمين';

// Set page title
$page_title = 'Create ' . $mod_name;

// Include header
include 'header.php';
?>

<!-- Main content -->
<main class="h-screen md:h-screen md:overflow-hidden overflow-auto md:pt-14 pt-24 md:px-8 px-4">
    <div class="container mx-auto">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl text-slate-900 font-bold"><?= $page_title ?></h1>
            <a href="list_<?= $mod_slug ?>.php" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Back to List</a>
        </div>
        <form id="create-form" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <div class="mb-4">
                <label class="block text-slate-900 text-sm font-bold mb-2" for="name">Name</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-slate-900" id="name" type="text" placeholder="Name">
            </div>
            <div class="mb-4">
                <label class="block text-slate-900 text-sm font-bold mb-2" for="email">Email</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-slate-900" id="email" type="email" placeholder="Email">
            </div>
            <div class="mb-4">
                <label class="block text-slate-900 text-sm font-bold mb-2" for="phone">Phone</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-slate-900" id="phone" type="text" placeholder="Phone">
            </div>
            <div class="mb-4">
                <label class="block text-slate-900 text-sm font-bold mb-2" for="address">Address</label>
                <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-slate-900" id="address" placeholder="Address"></textarea>
            </div>
            <div class="flex items-center justify-between">
                <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" type="submit">Create</button>
            </div>
        </form>
    </div>
</main>

<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            var formData = {
                'name': $('#name').val(),
                'email': $('#email').val(),
                'phone': $('#phone').val(),
                'address': $('#address').val()
            };
            $.ajax({
                type: 'POST',
                url: '../backend/<?= $mod_slug ?>.php',
                data: formData,
                success: function(data) {
                    window.location.href = 'list_<?= $mod_slug ?>.php';
                }
            });
        });
    });
</script>

<?php
// Include footer
include 'footer.php';
?>