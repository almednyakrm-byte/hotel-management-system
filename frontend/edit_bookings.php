**edit_bookings.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit;
}

// Get booking ID from URL
$id = $_GET['id'];

// Validate ID
if (empty($id) || !is_numeric($id)) {
    header('Location: list_bookings.php');
    exit;
}

// Fetch existing record details via GET
$booking = json_decode(file_get_contents('../backend/bookings.php?id=' . $id), true);

// Validate record
if (empty($booking)) {
    header('Location: list_bookings.php');
    exit;
}

// Set page title and mod slug
$page_title = 'Edit Booking';
$mod_slug = 'bookings';

// Include header and navigation
include 'header.php';
?>

<!-- Page content -->
<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12">
    <h1 class="text-3xl font-bold mb-4"><?= $page_title ?></h1>

    <!-- Form -->
    <form id="edit-booking-form" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name</label>
            <input type="text" id="name" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?= $booking['name'] ?>">
        </div>
        <div class="mb-4">
            <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
            <input type="email" id="email" name="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?= $booking['email'] ?>">
        </div>
        <div class="mb-4">
            <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">Phone</label>
            <input type="tel" id="phone" name="phone" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?= $booking['phone'] ?>">
        </div>
        <div class="mb-4">
            <label for="date" class="block text-gray-700 text-sm font-bold mb-2">Date</label>
            <input type="date" id="date" name="date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?= $booking['date'] ?>">
        </div>
        <div class="mb-4">
            <label for="time" class="block text-gray-700 text-sm font-bold mb-2">Time</label>
            <input type="time" id="time" name="time" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?= $booking['time'] ?>">
        </div>
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update Booking</button>
    </form>
</div>

<!-- Script to fetch existing record details via GET and populate form fields -->
<script>
    fetch('../backend/bookings.php?id=<?= $id ?>')
        .then(response => response.json())
        .then(data => {
            document.getElementById('name').value = data.name;
            document.getElementById('email').value = data.email;
            document.getElementById('phone').value = data.phone;
            document.getElementById('date').value = data.date;
            document.getElementById('time').value = data.time;
        })
        .catch(error => console.error(error));
</script>

<!-- Script to handle form submission via AJAX PUT request -->
<script>
    document.getElementById('edit-booking-form').addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(this);
        const id = <?= $id ?>;

        fetch('../backend/bookings.php', {
            method: 'PUT',
            body: formData,
            headers: {
                'X-CSRF-Token': '<?= $_SESSION['csrf_token'] ?>'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = 'list_bookings.php';
            } else {
                console.error(data.error);
            }
        })
        .catch(error => console.error(error));
    });
</script>

<?php
// Include footer
include 'footer.php';
?>


**bookings.php (backend)**

<?php
// Start session
session_start();

// Validate CSRF token
if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    header('Location: login.php');
    exit;
}

// Get booking ID from URL
$id = $_GET['id'];

// Validate ID
if (empty($id) || !is_numeric($id)) {
    header('Location: list_bookings.php');
    exit;
}

// Fetch existing record details from database
$booking = get_booking($id);

// Validate record
if (empty($booking)) {
    header('Location: list_bookings.php');
    exit;
}

// Update record in database
update_booking($id, $_POST);

// Output JSON response
header('Content-Type: application/json');
echo json_encode($booking);
exit;

// Helper functions
function get_booking($id) {
    // Database query to fetch existing record details
    // ...
}

function update_booking($id, $data) {
    // Database query to update record
    // ...
}
?>


**header.php and footer.php (not included in this code snippet)**

<!-- header.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
</head>
<body>
    <!-- navigation bar -->
    <nav class="bg-gray-200 text-gray-700 p-4">
        <!-- links -->
    </nav>
    <div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12">
        <!-- page content -->
    </div>
</body>
</html>

<!-- footer.php -->
<footer class="bg-gray-200 text-gray-700 p-4">
    <!-- copyright information -->
</footer>