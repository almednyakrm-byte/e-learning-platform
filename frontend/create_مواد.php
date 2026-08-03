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

// Module slug
$mod_slug = 'مواد';

// Page title
$page_title = 'Create ' . $mod_slug;

// Include header
include 'header.php';
?>

<!-- Content -->
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-slate-900"><?= $page_title ?></h1>
    <form id="create-form" class="mt-8 space-y-6">
        <div class="rounded-md shadow-sm space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-slate-900">Name</label>
                <input type="text" id="name" name="name" autocomplete="name" class="mt-1 block w-full rounded-md border border-slate-300 py-2 pl-3 pr-10 text-base text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-slate-900">Description</label>
                <textarea id="description" name="description" autocomplete="description" class="mt-1 block w-full rounded-md border border-slate-300 py-2 pl-3 pr-10 text-base text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
            </div>
            <div>
                <label for="quantity" class="block text-sm font-medium text-slate-900">Quantity</label>
                <input type="number" id="quantity" name="quantity" autocomplete="quantity" class="mt-1 block w-full rounded-md border border-slate-300 py-2 pl-3 pr-10 text-base text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div>
                <label for="unit_price" class="block text-sm font-medium text-slate-900">Unit Price</label>
                <input type="number" id="unit_price" name="unit_price" autocomplete="unit_price" class="mt-1 block w-full rounded-md border border-slate-300 py-2 pl-3 pr-10 text-base text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
        </div>
        <div>
            <button type="submit" class="inline-flex w-full justify-center rounded-md border border-transparent bg-indigo-500 py-2 px-4 text-base font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm">Create</button>
        </div>
    </form>
</div>

<!-- Include footer -->
<?php include 'footer.php'; ?>

<!-- AJAX JavaScript -->
<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/<?= $mod_slug ?>.php',
                data: $(this).serialize(),
                success: function() {
                    window.location.href = 'list_<?= $mod_slug ?>.php';
                }
            });
        });
    });
</script>