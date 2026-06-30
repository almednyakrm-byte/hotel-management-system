**edit_rooms.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get room ID from URL
$id = $_GET['id'];

// Validate room ID
if (empty($id)) {
    header('Location: list_rooms.php');
    exit;
}

// Fetch existing room details via AJAX
$roomDetails = json_decode(file_get_contents('../backend/rooms.php?id=' . $id), true);

// Set page title
$pageTitle = 'Edit Room';

// Include header
include '../includes/header.php';
?>

<!-- Page content -->
<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-24">
    <h1 class="text-3xl font-bold text-gray-900 mb-4"><?= $pageTitle ?></h1>

    <!-- Form -->
    <form id="edit-room-form" class="bg-white rounded shadow-md p-4">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Room Name</label>
                <input type="text" id="name" name="name" class="block w-full px-4 py-2 text-sm text-gray-700 border border-gray-200 rounded-md focus:ring-blue-500 focus:border-blue-500" value="<?= $roomDetails['name'] ?>">
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Room Description</label>
                <textarea id="description" name="description" class="block w-full px-4 py-2 text-sm text-gray-700 border border-gray-200 rounded-md focus:ring-blue-500 focus:border-blue-500"><?= $roomDetails['description'] ?></textarea>
            </div>
            <div>
                <label for="capacity" class="block text-sm font-medium text-gray-700">Room Capacity</label>
                <input type="number" id="capacity" name="capacity" class="block w-full px-4 py-2 text-sm text-gray-700 border border-gray-200 rounded-md focus:ring-blue-500 focus:border-blue-500" value="<?= $roomDetails['capacity'] ?>">
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Room Status</label>
                <select id="status" name="status" class="block w-full px-4 py-2 text-sm text-gray-700 border border-gray-200 rounded-md focus:ring-blue-500 focus:border-blue-500">
                    <option value="active" <?= $roomDetails['status'] == 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= $roomDetails['status'] == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>
        </div>

        <!-- Submit button -->
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update Room</button>
    </form>
</div>

<!-- JavaScript -->
<script>
    // Fetch existing room details via AJAX
    fetch('../backend/rooms.php?id=' + <?= $id ?>)
        .then(response => response.json())
        .then(data => {
            // Populate form fields
            document.getElementById('name').value = data.name;
            document.getElementById('description').value = data.description;
            document.getElementById('capacity').value = data.capacity;
            document.getElementById('status').value = data.status;
        })
        .catch(error => console.error(error));

    // Submit form via AJAX
    document.getElementById('edit-room-form').addEventListener('submit', event => {
        event.preventDefault();

        // Get form data
        const formData = new FormData(event.target);

        // Send PUT request to update room
        fetch('../backend/rooms.php', {
            method: 'PUT',
            body: formData,
            headers: {
                'X-CSRF-Token': '<?= $_SESSION['csrf_token'] ?>'
            }
        })
            .then(response => response.json())
            .then(data => {
                // Redirect to list rooms page
                window.location.href = 'list_rooms.php';
            })
            .catch(error => console.error(error));
    });
</script>

<!-- Include footer -->
<?php include '../includes/footer.php'; ?>


**rooms.php (backend)**

<?php
// Include database connection
include '../includes/db.php';

// Get room ID from URL
$id = $_GET['id'];

// Validate room ID
if (empty($id)) {
    exit;
}

// Fetch existing room details
$room = $db->prepare('SELECT * FROM rooms WHERE id = ?');
$room->execute([$id]);
$roomDetails = $room->fetch();

// Output room details as JSON
echo json_encode($roomDetails);


**list_rooms.php (backend)**

<?php
// Include database connection
include '../includes/db.php';

// Fetch all rooms
$rooms = $db->query('SELECT * FROM rooms')->fetchAll();

// Output rooms as HTML table
echo '<table class="table-auto w-full">';
echo '<thead>';
echo '<tr>';
echo '<th>Room Name</th>';
echo '<th>Room Description</th>';
echo '<th>Room Capacity</th>';
echo '<th>Room Status</th>';
echo '<th>Actions</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';

foreach ($rooms as $room) {
    echo '<tr>';
    echo '<td>' . $room['name'] . '</td>';
    echo '<td>' . $room['description'] . '</td>';
    echo '<td>' . $room['capacity'] . '</td>';
    echo '<td>' . ($room['status'] == 'active' ? 'Active' : 'Inactive') . '</td>';
    echo '<td>';
    echo '<a href="edit_rooms.php?id=' . $room['id'] . '">Edit</a> | ';
    echo '<a href="delete_rooms.php?id=' . $room['id'] . '">Delete</a>';
    echo '</td>';
    echo '</tr>';
}

echo '</tbody>';
echo '</table>';


**delete_rooms.php (backend)**

<?php
// Include database connection
include '../includes/db.php';

// Get room ID from URL
$id = $_GET['id'];

// Validate room ID
if (empty($id)) {
    exit;
}

// Delete room
$db->prepare('DELETE FROM rooms WHERE id = ?')->execute([$id]);

// Redirect to list rooms page
header('Location: list_rooms.php');
exit;