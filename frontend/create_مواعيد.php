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

// Set module slug
$mod_slug = 'مواعيد';

// Set page title
$page_title = 'Create مواعيد';

// Set form action
$form_action = '../backend/' . $mod_slug . '.php';

// Set redirect URL
$redirect_url = 'list_' . $mod_slug . '.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-5xl mx-auto p-4 sm:p-6 md:p-8 bg-white rounded-xl shadow-md">
        <h2 class="text-lg font-medium text-slate-900">Create <?php echo $mod_slug; ?></h2>
        <form id="create-form" method="post">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label for="title" class="block text-sm font-medium text-slate-900">Title</label>
                    <input type="text" id="title" name="title" class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div>
                    <label for="date" class="block text-sm font-medium text-slate-900">Date</label>
                    <input type="date" id="date" name="date" class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div>
                    <label for="time" class="block text-sm font-medium text-slate-900">Time</label>
                    <input type="time" id="time" name="time" class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium text-slate-900">Description</label>
                    <textarea id="description" name="description" class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                </div>
            </div>
            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-500 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Create</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#create-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '../backend/<?php echo $mod_slug; ?>.php',
                    data: $(this).serialize(),
                    success: function() {
                        window.location.href = '<?php echo $redirect_url; ?>';
                    }
                });
            });
        });
    </script>
</body>
</html>