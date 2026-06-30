**create_rooms.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
include 'header.php';
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-12">
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6 lg:p-8 xl:p-8">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Create New Room</h2>
        <form id="create-room-form" class="space-y-4">
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="room_name">Room Name</label>
                    <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="room_name" type="text" placeholder="Room Name">
                </div>
                <div class="w-full md:w-1/2 px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="room_capacity">Room Capacity</label>
                    <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="room_capacity" type="number" placeholder="Room Capacity">
                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="room_type">Room Type</label>
                    <select class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="room_type">
                        <option value="">Select Room Type</option>
                        <option value="Single">Single</option>
                        <option value="Double">Double</option>
                        <option value="Suite">Suite</option>
                    </select>
                </div>
                <div class="w-full md:w-1/2 px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="room_status">Room Status</label>
                    <select class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="room_status">
                        <option value="">Select Room Status</option>
                        <option value="Available">Available</option>
                        <option value="Occupied">Occupied</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Create Room</button>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#create-room-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/rooms.php',
                data: formData,
                success: function(response) {
                    if (response == 'success') {
                        window.location.href = 'list_rooms.php';
                    } else {
                        alert('Error creating room');
                    }
                }
            });
        });
    });
</script>

<?php
// Include footer
include 'footer.php';
?>


**rooms.php (backend)**

<?php
// Include database connection
include 'db.php';

// Check if form data is submitted
if (isset($_POST['room_name']) && isset($_POST['room_capacity']) && isset($_POST['room_type']) && isset($_POST['room_status'])) {
    // Prepare SQL query
    $sql = "INSERT INTO rooms (room_name, room_capacity, room_type, room_status) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $_POST['room_name'], $_POST['room_capacity'], $_POST['room_type'], $_POST['room_status']);
    // Execute query
    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'Error creating room';
    }
    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>

Note: This code assumes you have a `db.php` file that establishes a connection to your database and a `header.php` and `footer.php` file that includes the HTML header and footer respectively. You will need to modify the code to fit your specific database schema and requirements.