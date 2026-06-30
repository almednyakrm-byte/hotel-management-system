<?php
// edit_حجوزات-الفندق.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: list_حجوزات-الفندق.php');
    exit;
}

$id = $_GET['id'];

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل حجوزات الفندق</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto p-4 pt-6 mt-10 bg-white rounded-xl shadow-md">
        <h1 class="text-3xl font-bold mb-4">تعديل حجوزات الفندق</h1>
        <form id="edit-form">
            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">اسم الحجز</label>
                <input type="text" id="name" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
                <label for="date" class="block text-gray-700 text-sm font-bold mb-2">تاريخ الحجز</label>
                <input type="date" id="date" name="date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
                <label for="room_number" class="block text-gray-700 text-sm font-bold mb-2">رقم الغرفة</label>
                <input type="number" id="room_number" name="room_number" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">تعديل</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            var id = '<?php echo $id; ?>';
            $.ajax({
                type: 'GET',
                url: '../backend/حجوزات-الفندق.php?id=' + id,
                dataType: 'json',
                success: function(data) {
                    $('#name').val(data.name);
                    $('#date').val(data.date);
                    $('#room_number').val(data.room_number);
                }
            });

            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = {
                    'name': $('#name').val(),
                    'date': $('#date').val(),
                    'room_number': $('#room_number').val(),
                    'id': id
                };
                $.ajax({
                    type: 'PUT',
                    url: '../backend/حجوزات-الفندق.php',
                    data: JSON.stringify(formData),
                    contentType: 'application/json',
                    success: function(data) {
                        window.location.href = 'list_حجوزات-الفندق.php';
                    }
                });
            });
        });
    </script>
</body>
</html>