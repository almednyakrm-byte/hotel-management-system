**create_غرف.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Include header
include 'header.php';

// Include navigation
include 'navigation.php';

// Include form
include 'create_غرف_form.php';

// Include footer
include 'footer.php';
?>


**create_غرف_form.php**

<div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
    <h2 class="text-2xl font-bold text-slate-900 mb-4">Create New غرف</h2>
    <form id="create_غرف_form" class="space-y-6">
        <div class="grid grid-cols-1 gap-6">
            <div>
                <label for="name" class="block text-sm font-medium text-slate-900">Name</label>
                <input type="text" id="name" name="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-slate-900">Description</label>
                <textarea id="description" name="description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
            </div>
            <div>
                <label for="capacity" class="block text-sm font-medium text-slate-900">Capacity</label>
                <input type="number" id="capacity" name="capacity" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label for="location" class="block text-sm font-medium text-slate-900">Location</label>
                <input type="text" id="location" name="location" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
        </div>
        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-500 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Create</button>
    </form>
</div>

<script>
    $(document).ready(function() {
        $('#create_غرف_form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/غرف.php',
                data: $(this).serialize(),
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_غرف.php';
                    } else {
                        alert('Error creating غرف');
                    }
                }
            });
        });
    });
</script>


**header.php**

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New غرف</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body>
    <?php include 'create_غرف.php'; ?>
</body>
</html>


**footer.php**

</html>


**navigation.php**

<nav class="bg-slate-900 py-2">
    <div class="container mx-auto px-4 flex justify-between items-center">
        <a href="index.php" class="text-white font-bold text-lg">Home</a>
        <ul class="flex items-center space-x-4">
            <li><a href="list_غرف.php" class="text-white hover:text-indigo-500">List غرف</a></li>
            <li><a href="create_غرف.php" class="text-white hover:text-indigo-500">Create غرف</a></li>
        </ul>
    </div>
</nav>