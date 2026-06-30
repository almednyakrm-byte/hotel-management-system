<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Define the module slug
$mod_slug = 'نزلاء';

// Define the page title
$page_title = 'Add New نزلاء';

// Include the header file
include 'header.php';
?>

<main class="h-full overflow-y-auto p-4">
    <div class="container mx-auto">
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <div class="lg:col-span-1">
                <h2 class="text-2xl font-bold text-slate-900"><?= $page_title ?></h2>
            </div>
            <div class="lg:col-span-1">
                <a href="list_<?= $mod_slug ?>.php" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                    Back to List
                </a>
            </div>
        </div>
        <form id="create-form" class="mt-6">
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <div class="lg:col-span-1">
                    <label for="name" class="block text-sm font-medium text-slate-900">Name</label>
                    <input type="text" id="name" name="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div class="lg:col-span-1">
                    <label for="email" class="block text-sm font-medium text-slate-900">Email</label>
                    <input type="email" id="email" name="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div class="lg:col-span-1">
                    <label for="phone" class="block text-sm font-medium text-slate-900">Phone</label>
                    <input type="text" id="phone" name="phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div class="lg:col-span-1">
                    <label for="address" class="block text-sm font-medium text-slate-900">Address</label>
                    <input type="text" id="address" name="address" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
            </div>
            <button type="submit" class="mt-6 bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                Create
            </button>
        </form>
    </div>
</main>

<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/<?= $mod_slug ?>.php',
                data: $(this).serialize(),
                success: function(data) {
                    window.location.href = 'list_<?= $mod_slug ?>.php';
                }
            });
        });
    });
</script>

<?php
// Include the footer file
include 'footer.php';
?>