<?php
// create_rooms.php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

include_once '../config.php';

$mod_slug = 'rooms';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Room</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-md mx-auto p-4 bg-gray-200 rounded-lg shadow-md mt-10">
        <h2 class="text-2xl text-blue-500 font-bold mb-4">Create Room</h2>
        <form id="create-room-form">
            <div class="mb-4">
                <label for="room_name" class="block text-gray-700 text-sm font-bold mb-2">Room Name:</label>
                <input type="text" id="room_name" name="room_name" class="block w-full p-2 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div class="mb-4">
                <label for="room_type" class="block text-gray-700 text-sm font-bold mb-2">Room Type:</label>
                <select id="room_type" name="room_type" class="block w-full p-2 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="">Select Room Type</option>
                    <option value="single">Single</option>
                    <option value="double">Double</option>
                    <option value="suite">Suite</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="room_capacity" class="block text-gray-700 text-sm font-bold mb-2">Room Capacity:</label>
                <input type="number" id="room_capacity" name="room_capacity" class="block w-full p-2 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div class="mb-4">
                <label for="room_rate" class="block text-gray-700 text-sm font-bold mb-2">Room Rate:</label>
                <input type="number" id="room_rate" name="room_rate" class="block w-full p-2 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:ring-blue-500">Create Room</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#create-room-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '../backend/rooms.php',
                    data: $(this).serialize(),
                    success: function(response) {
                        window.location.href = 'list_<?php echo $mod_slug; ?>.php';
                    }
                });
            });
        });
    </script>
</body>
</html>