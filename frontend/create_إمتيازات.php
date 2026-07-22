**create_إمتيازات.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../backend/db.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);

    // Check if fields are not empty
    if (!empty($name) && !empty($description)) {
        // Insert new record into database
        $sql = "INSERT INTO إمتيازات (name, description) VALUES ('$name', '$description')";
        $result = mysqli_query($conn, $sql);

        // Check if insertion was successful
        if ($result) {
            // Redirect back to list page
            header('Location: list_إمتيازات.php');
            exit;
        } else {
            // Display error message
            $error = 'Error inserting record';
        }
    } else {
        // Display error message
        $error = 'Please fill in all fields';
    }
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة إمتياز</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
        }
        .bg-slate-900 {
            background-color: #1a1a1a;
        }
        .text-indigo-500 {
            color: #6b6bcf;
        }
    </style>
</head>
<body class="bg-slate-900">
    <div class="container mx-auto p-4">
        <h1 class="text-3xl text-indigo-500 mb-4">إضافة إمتياز</h1>
        <form id="create-form" method="post" class="bg-white p-4 rounded shadow-md">
            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">اسم الإمتياز:</label>
                <input type="text" id="name" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="mb-4">
                <label for="description" class="block text-gray-700 text-sm font-bold mb-2">وصف الإمتياز:</label>
                <textarea id="description" name="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required></textarea>
            </div>
            <button type="submit" name="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">إضافة</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#create-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '../backend/إمتيازات.php',
                    data: $(this).serialize(),
                    success: function(data) {
                        if (data === 'success') {
                            window.location.href = 'list_إمتيازات.php';
                        } else {
                            alert('Error creating record');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**إمتيازات.php (backend)**

<?php
// Include database connection
require_once '../backend/db.php';

// Check if form data has been sent
if (isset($_POST['submit'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);

    // Insert new record into database
    $sql = "INSERT INTO إمتيازات (name, description) VALUES ('$name', '$description')";
    $result = mysqli_query($conn, $sql);

    // Check if insertion was successful
    if ($result) {
        echo 'success';
    } else {
        echo 'Error inserting record';
    }
}

// Close database connection
mysqli_close($conn);
?>