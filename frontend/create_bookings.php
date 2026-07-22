<?php
// Start the session
session_start();

// Validate the session
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Define the module slug
$mod_slug = 'bookings';

// Include the database connection
require_once '../backend/db.php';

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle the form submission
    // This will be handled by AJAX
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-md mx-auto p-4 mt-10 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl text-blue-500 mb-4">Create Booking</h2>
        <form id="create-booking-form">
            <div class="mb-4">
                <label for="customer_name" class="block text-gray-700 text-sm font-bold mb-2">Customer Name</label>
                <input type="text" id="customer_name" name="customer_name" class="block w-full p-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="mb-4">
                <label for="booking_date" class="block text-gray-700 text-sm font-bold mb-2">Booking Date</label>
                <input type="date" id="booking_date" name="booking_date" class="block w-full p-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="mb-4">
                <label for="booking_time" class="block text-gray-700 text-sm font-bold mb-2">Booking Time</label>
                <input type="time" id="booking_time" name="booking_time" class="block w-full p-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="mb-4">
                <label for="service" class="block text-gray-700 text-sm font-bold mb-2">Service</label>
                <select id="service" name="service" class="block w-full p-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Select a service</option>
                    <option value="service1">Service 1</option>
                    <option value="service2">Service 2</option>
                    <option value="service3">Service 3</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="status" class="block text-gray-700 text-sm font-bold mb-2">Status</label>
                <select id="status" name="status" class="block w-full p-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Select a status</option>
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <button type="submit" class="w-full p-2 bg-blue-500 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-blue-500 focus:border-blue-500">Create Booking</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#create-booking-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '../backend/bookings.php',
                    data: $(this).serialize(),
                    success: function(data) {
                        window.location.href = 'list_bookings.php';
                    }
                });
            });
        });
    </script>
</body>
</html>