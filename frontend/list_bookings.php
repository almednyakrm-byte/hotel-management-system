**list_bookings.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookings</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .bg-blue-500 {
            background-color: #1a73fe;
        }
        .text-gray-200 {
            color: #f7fafc;
        }
    </style>
</head>
<body class="bg-gray-200">
    <div class="container mx-auto p-4">
        <div class="flex justify-between mb-4">
            <a href="index.php" class="text-blue-500 hover:text-blue-700">Back to Dashboard</a>
            <div class="flex items-center">
                <p class="text-gray-700 mr-2">Logged in as: <?= $_SESSION['username'] ?></p>
                <a href="logout.php" class="text-blue-500 hover:text-blue-700">Logout</a>
            </div>
        </div>
        <div class="flex justify-between mb-4">
            <h1 class="text-2xl text-gray-700">Bookings</h1>
            <a href="create_bookings.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add New Item</a>
        </div>
        <div class="flex justify-between mb-4">
            <input type="search" id="search" class="w-full p-2 pl-10 text-sm text-gray-700" placeholder="Search...">
            <button id="search-btn" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Search</button>
        </div>
        <table class="w-full">
            <thead>
                <tr>
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Email</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody id="bookings-list">
                <?php
                // Fetch list records from backend
                $response = file_get_contents('../backend/bookings.php');
                $bookings = json_decode($response, true);
                foreach ($bookings as $booking) {
                    ?>
                    <tr>
                        <td class="px-4 py-2"><?= $booking['id'] ?></td>
                        <td class="px-4 py-2"><?= $booking['name'] ?></td>
                        <td class="px-4 py-2"><?= $booking['email'] ?></td>
                        <td class="px-4 py-2">
                            <a href="edit_bookings.php?id=<?= $booking['id'] ?>" class="text-blue-500 hover:text-blue-700">Edit</a>
                            <button class="text-red-500 hover:text-red-700" onclick="deleteBooking(<?= $booking['id'] ?>)">Delete</button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        // Search bar filtering
        const searchInput = document.getElementById('search');
        const searchBtn = document.getElementById('search-btn');
        const bookingsList = document.getElementById('bookings-list');

        searchBtn.addEventListener('click', () => {
            const searchTerm = searchInput.value.toLowerCase();
            const bookings = JSON.parse('<?php echo json_encode($bookings); ?>');
            const filteredBookings = bookings.filter(booking => booking.name.toLowerCase().includes(searchTerm) || booking.email.toLowerCase().includes(searchTerm));
            renderBookingsList(filteredBookings);
        });

        // Delete booking via AJAX
        function deleteBooking(id) {
            fetch('../backend/bookings.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    renderBookingsList(data.bookings);
                } else {
                    alert('Error deleting booking');
                }
            })
            .catch(error => console.error('Error:', error));
        }

        // Render bookings list
        function renderBookingsList(bookings) {
            const bookingsListHtml = bookings.map(booking => `
                <tr>
                    <td class="px-4 py-2">${booking.id}</td>
                    <td class="px-4 py-2">${booking.name}</td>
                    <td class="px-4 py-2">${booking.email}</td>
                    <td class="px-4 py-2">
                        <a href="edit_bookings.php?id=${booking.id}" class="text-blue-500 hover:text-blue-700">Edit</a>
                        <button class="text-red-500 hover:text-red-700" onclick="deleteBooking(${booking.id})">Delete</button>
                    </td>
                </tr>
            `).join('');
            bookingsList.innerHTML = bookingsListHtml;
        }

        // Fetch list records from backend on page load
        fetch('../backend/bookings.php')
        .then(response => response.json())
        .then(data => {
            renderBookingsList(data.bookings);
        })
        .catch(error => console.error('Error:', error));
    </script>
</body>
</html>

**backend/bookings.php**

<?php
// Fetch list records from database
$bookings = array(
    array('id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com'),
    array('id' => 2, 'name' => 'Jane Doe', 'email' => 'jane@example.com'),
    // ...
);

// Return list records as JSON
header('Content-Type: application/json');
echo json_encode($bookings);

Note: This is a basic implementation and you should replace the `backend/bookings.php` file with your actual database connection and query to fetch the list records.