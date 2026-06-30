**create_حجوزات.php**

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
    $date = trim($_POST['date']);
    $time = trim($_POST['time']);
    $price = trim($_POST['price']);

    // Check for empty fields
    if (empty($name) || empty($description) || empty($date) || empty($time) || empty($price)) {
        $error = 'Please fill in all fields';
    } else {
        // Insert data into database
        $sql = "INSERT INTO حجوزات (name, description, date, time, price) VALUES ('$name', '$description', '$date', '$time', '$price')";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            // Redirect back to list page
            header('Location: list_حجوزات.php');
            exit;
        } else {
            $error = 'Error inserting data';
        }
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
    <title>حجوزات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded shadow-md">
        <h1 class="text-2xl font-bold text-slate-900 mb-4">إضافة حجوزات جديدة</h1>
        <form action="" method="post" class="space-y-4">
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="name">اسم الحجز</label>
                    <input type="text" name="name" id="name" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" required>
                </div>
                <div class="w-full md:w-1/2 px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="description">وصف الحجز</label>
                    <textarea name="description" id="description" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" required></textarea>
                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="date">تاريخ الحجز</label>
                    <input type="date" name="date" id="date" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" required>
                </div>
                <div class="w-full md:w-1/2 px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="time">وقت الحجز</label>
                    <input type="time" name="time" id="time" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" required>
                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="price">سعر الحجز</label>
                    <input type="number" name="price" id="price" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" required>
                </div>
            </div>
            <button type="submit" name="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">إضافة الحجز</button>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            $('#create_form').submit(function(event) {
                event.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'POST',
                    url: '../backend/حجوزات.php',
                    data: formData,
                    success: function(response) {
                        if (response == 'success') {
                            window.location.href = 'list_حجوزات.php';
                        } else {
                            alert('Error adding data');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>

Note: Make sure to replace `../backend/حجوزات.php` with the actual URL of your backend script that handles the form submission. Also, make sure to include the necessary JavaScript libraries (e.g. jQuery) and CSS files in your HTML file.