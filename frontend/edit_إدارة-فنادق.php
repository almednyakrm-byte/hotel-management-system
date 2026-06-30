<!-- edit_إدارة-فنادق.php -->

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get the ID from URL
$id = $_GET['id'];

// Fetch existing record details
$existingRecord = json_decode(file_get_contents('../backend/إدارة-فنادق.php?id=' . $id), true);

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة فنادق</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">تعديل إدارة فنادق</h1>
        <form id="edit-form" class="bg-white p-4 rounded shadow-md">
            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">اسم الإدارة</label>
                <input type="text" id="name" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?= $existingRecord['name'] ?>">
            </div>
            <div class="mb-4">
                <label for="address" class="block text-gray-700 text-sm font-bold mb-2">العنوان</label>
                <input type="text" id="address" name="address" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?= $existingRecord['address'] ?>">
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">رقم الهاتف</label>
                <input type="text" id="phone" name="phone" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?= $existingRecord['phone'] ?>">
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">تعديل</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/إدارة-فنادق.php',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'list_إدارة-فنادق.php';
                        } else {
                            alert('Error updating record');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>



// backend/إدارة-فنادق.php

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    exit;
}

// Get the ID
$id = $_GET['id'];

// Fetch existing record details from database
// Replace with your actual database query
$existingRecord = [
    'id' => $id,
    'name' => 'إدارة فنادق',
    'address' => 'العنوان',
    'phone' => '0123456789'
];

// Output JSON response
header('Content-Type: application/json');
echo json_encode($existingRecord);
exit;
?>