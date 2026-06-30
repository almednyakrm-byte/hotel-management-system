**create_bookings.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
include 'header.php';
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:px-12 xl:px-24">
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6 lg:p-8 xl:p-12">
        <h2 class="text-lg font-bold text-gray-800 mb-2">Create Booking</h2>
        <form id="create-booking-form">
            <div class="mb-4">
                <label for="customer_name" class="block text-sm font-medium text-gray-700">Customer Name</label>
                <input type="text" id="customer_name" name="customer_name" class="block w-full px-4 py-2 text-sm text-gray-700 bg-gray-200 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="mb-4">
                <label for="booking_date" class="block text-sm font-medium text-gray-700">Booking Date</label>
                <input type="date" id="booking_date" name="booking_date" class="block w-full px-4 py-2 text-sm text-gray-700 bg-gray-200 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="mb-4">
                <label for="start_time" class="block text-sm font-medium text-gray-700">Start Time</label>
                <input type="time" id="start_time" name="start_time" class="block w-full px-4 py-2 text-sm text-gray-700 bg-gray-200 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="mb-4">
                <label for="end_time" class="block text-sm font-medium text-gray-700">End Time</label>
                <input type="time" id="end_time" name="end_time" class="block w-full px-4 py-2 text-sm text-gray-700 bg-gray-200 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="mb-4">
                <label for="service" class="block text-sm font-medium text-gray-700">Service</label>
                <select id="service" name="service" class="block w-full px-4 py-2 text-sm text-gray-700 bg-gray-200 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Select Service</option>
                    <option value="Service 1">Service 1</option>
                    <option value="Service 2">Service 2</option>
                    <option value="Service 3">Service 3</option>
                </select>
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Create Booking</button>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#create-booking-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/bookings.php',
                data: formData,
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_bookings.php';
                    } else {
                        alert('Error creating booking');
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


**bookings.php (backend)**

<?php
// Include database connection
include 'db.php';

// Check if form data is submitted
if (isset($_POST['customer_name']) && isset($_POST['booking_date']) && isset($_POST['start_time']) && isset($_POST['end_time']) && isset($_POST['service'])) {
    // Prepare SQL query
    $sql = "INSERT INTO bookings (customer_name, booking_date, start_time, end_time, service) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $_POST['customer_name'], $_POST['booking_date'], $_POST['start_time'], $_POST['end_time'], $_POST['service']);
    // Execute query
    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'Error creating booking';
    }
    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>


Note: This code assumes you have a `db.php` file that establishes a connection to your database and a `footer.php` file that includes the closing HTML tags. You'll need to modify the code to match your specific database schema and backend setup.