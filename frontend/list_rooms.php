**list_rooms.php**

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
    <title>Rooms</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .bg-blue-500 {
            background-color: #1a73e8;
        }
        .text-gray-200 {
            color: #f7fafc;
        }
    </style>
</head>
<body class="bg-gray-200">
    <div class="container mx-auto p-4">
        <header class="bg-blue-500 p-4 mb-4">
            <nav class="flex justify-between">
                <a href="index.php" class="text-gray-200 hover:text-white">Back to Index</a>
                <div class="flex items-center">
                    <span class="text-gray-200 mr-2">Hello, <?= $_SESSION['username'] ?></span>
                    <a href="logout.php" class="text-gray-200 hover:text-white">Logout</a>
                </div>
            </nav>
        </header>
        <main class="bg-white p-4 rounded shadow-md">
            <h2 class="text-lg font-bold mb-4">Rooms</h2>
            <div class="flex justify-between mb-4">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_rooms.php'">Add New Item</button>
                <input type="search" id="search" class="w-full p-2 pl-10 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-200" placeholder="Search...">
            </div>
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Name</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody id="rooms-list">
                    <?php
                    // Fetch records from backend
                    $response = file_get_contents('../backend/rooms.php');
                    $rooms = json_decode($response, true);
                    foreach ($rooms as $room) {
                        ?>
                        <tr>
                            <td class="px-4 py-2"><?= $room['id'] ?></td>
                            <td class="px-4 py-2"><?= $room['name'] ?></td>
                            <td class="px-4 py-2">
                                <a href="edit_rooms.php?id=<?= $room['id'] ?>" class="text-blue-500 hover:text-blue-700">Edit</a>
                                <button class="ml-2 text-red-500 hover:text-red-700" onclick="deleteRoom(<?= $room['id'] ?>)">Delete</button>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </main>
    </div>

    <script>
        // Search bar filtering
        const searchInput = document.getElementById('search');
        const roomsList = document.getElementById('rooms-list');

        searchInput.addEventListener('input', () => {
            const searchTerm = searchInput.value.toLowerCase();
            const rooms = roomsList.children;
            for (let i = 0; i < rooms.length; i++) {
                const room = rooms[i];
                const name = room.children[1].textContent.toLowerCase();
                if (name.includes(searchTerm)) {
                    room.style.display = 'table-row';
                } else {
                    room.style.display = 'none';
                }
            }
        });

        // AJAX delete request
        function deleteRoom(id) {
            fetch('../backend/rooms.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Room deleted successfully!');
                    location.reload();
                } else {
                    alert('Error deleting room!');
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</body>
</html>

**rooms.php** (backend)

<?php
// Database connection
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch records
$query = "SELECT * FROM rooms";
$result = $conn->query($query);

$rooms = array();
while ($row = $result->fetch_assoc()) {
    $rooms[] = $row;
}

// JSON encode records
echo json_encode($rooms);

// Close connection
$conn->close();
?>

Note: Replace `'localhost'`, `'username'`, `'password'`, and `'database'` with your actual database credentials and name.