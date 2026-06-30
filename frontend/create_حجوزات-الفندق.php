<?php
// create_حجوزات-الفندق.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

include_once '../config.php';
include_once '../backend/connection.php';

$mod_slug = 'حجوزات-الفندق';

?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة جديد - <?php echo $mod_slug; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-5xl mx-auto p-4 sm:p-6 md:p-8">
        <h1 class="text-3xl font-bold mb-4">إضافة جديد - <?php echo $mod_slug; ?></h1>
        <form id="create-form">
            <div class="mb-4">
                <label for="guest_name" class="block text-sm font-medium text-gray-700">اسم النزيل</label>
                <input type="text" id="guest_name" name="guest_name" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>
            <div class="mb-4">
                <label for="room_number" class="block text-sm font-medium text-gray-700">رقم الغرفة</label>
                <input type="number" id="room_number" name="room_number" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>
            <div class="mb-4">
                <label for="arrival_date" class="block text-sm font-medium text-gray-700">تاريخ الوصول</label>
                <input type="date" id="arrival_date" name="arrival_date" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>
            <div class="mb-4">
                <label for="departure_date" class="block text-sm font-medium text-gray-700">تاريخ المغادرة</label>
                <input type="date" id="departure_date" name="departure_date" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>
            <div class="mb-4">
                <label for="room_type" class="block text-sm font-medium text-gray-700">نوع الغرفة</label>
                <select id="room_type" name="room_type" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="">اختر نوع الغرفة</option>
                    <option value="single">غرفة فردية</option>
                    <option value="double">غرفة مزدوجة</option>
                    <option value="suite">سويت</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="price" class="block text-sm font-medium text-gray-700">السعر</label>
                <input type="number" id="price" name="price" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>
            <button type="submit" class="py-2 px-4 bg-indigo-500 text-white rounded-md hover:bg-indigo-700">إضافة</button>
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
                    success: function(data) {
                        window.location.href = 'list_<?php echo $mod_slug; ?>.php';
                    }
                });
            });
        });
    </script>
</body>
</html>