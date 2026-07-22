<?php
// edit_rooms.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: list_rooms.php');
    exit;
}

$id = $_GET['id'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Room</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto p-4 pt-6 mt-10 bg-gray-200 rounded-lg shadow-md">
        <h2 class="text-2xl text-blue-500 mb-4">Edit Room</h2>
        <form id="edit-room-form">
            <div class="mb-4">
                <label for="room_name" class="block text-gray-700 text-sm font-bold mb-2">Room Name:</label>
                <input type="text" id="room_name" name="room_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
                <label for="room_description" class="block text-gray-700 text-sm font-bold mb-2">Room Description:</label>
                <textarea id="room_description" name="room_description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
            </div>
            <div class="mb-4">
                <label for="room_capacity" class="block text-gray-700 text-sm font-bold mb-2">Room Capacity:</label>
                <input type="number" id="room_capacity" name="room_capacity" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update Room</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            const id = <?= $id ?>;
            $.ajax({
                type: 'GET',
                url: '../backend/rooms.php?id=' + id,
                dataType: 'json',
                success: function(data) {
                    $('#room_name').val(data.room_name);
                    $('#room_description').val(data.room_description);
                    $('#room_capacity').val(data.room_capacity);
                }
            });

            $('#edit-room-form').submit(function(e) {
                e.preventDefault();
                const formData = {
                    room_name: $('#room_name').val(),
                    room_description: $('#room_description').val(),
                    room_capacity: $('#room_capacity').val()
                };

                $.ajax({
                    type: 'PUT',
                    url: '../backend/rooms.php',
                    data: JSON.stringify(formData),
                    contentType: 'application/json',
                    success: function() {
                        window.location.href = 'list_rooms.php';
                    }
                });
            });
        });
    </script>
</body>
</html>