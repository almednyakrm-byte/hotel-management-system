**create_فواتير-الفندق.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/database.php';

// Create form data array
$data = array();

// Check if form is submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $total = trim($_POST['total']);
    $date = trim($_POST['date']);

    // Check if all fields are filled
    if (!empty($name) && !empty($description) && !empty($total) && !empty($date)) {
        // Insert data into database
        $query = "INSERT INTO فواتير_الفندق (name, description, total, date) VALUES ('$name', '$description', '$total', '$date')";
        $result = mysqli_query($conn, $query);

        // Check if data is inserted successfully
        if ($result) {
            // Redirect back to list_{mod_slug}.php
            header('Location: list_فواتير-الفندق.php');
            exit;
        } else {
            // Display error message
            $data['error'] = 'Error inserting data';
        }
    } else {
        // Display error message
        $data['error'] = 'Please fill all fields';
    }
}

// Include header
require_once '../includes/header.php';

// Include form
?>

<div class="container mx-auto p-4">
    <div class="bg-white rounded-lg shadow-md p-4">
        <h2 class="text-lg font-bold mb-2">Create New فواتير الفندق</h2>
        <form id="create-form" method="post">
            <div class="mb-4">
                <label for="name" class="block text-sm font-bold mb-2">Name:</label>
                <input type="text" id="name" name="name" class="block w-full p-2 border border-gray-300 rounded-lg" required>
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-bold mb-2">Description:</label>
                <textarea id="description" name="description" class="block w-full p-2 border border-gray-300 rounded-lg" required></textarea>
            </div>
            <div class="mb-4">
                <label for="total" class="block text-sm font-bold mb-2">Total:</label>
                <input type="number" id="total" name="total" class="block w-full p-2 border border-gray-300 rounded-lg" required>
            </div>
            <div class="mb-4">
                <label for="date" class="block text-sm font-bold mb-2">Date:</label>
                <input type="date" id="date" name="date" class="block w-full p-2 border border-gray-300 rounded-lg" required>
            </div>
            <button type="submit" name="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">Create</button>
        </form>
        <?php if (isset($data['error'])) : ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 mt-4" role="alert">
                <?= $data['error'] ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
// Include footer
require_once '../includes/footer.php';
?>


**../backend/فواتير-الفندق.php**

<?php
// Include database connection
require_once '../config/database.php';

// Check if data is posted
if (isset($_POST['name']) && isset($_POST['description']) && isset($_POST['total']) && isset($_POST['date'])) {
    // Insert data into database
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $total = trim($_POST['total']);
    $date = trim($_POST['date']);

    $query = "INSERT INTO فواتير_الفندق (name, description, total, date) VALUES ('$name', '$description', '$total', '$date')";
    $result = mysqli_query($conn, $query);

    // Check if data is inserted successfully
    if ($result) {
        // Output success message
        echo json_encode(array('success' => true));
    } else {
        // Output error message
        echo json_encode(array('success' => false, 'error' => 'Error inserting data'));
    }
} else {
    // Output error message
    echo json_encode(array('success' => false, 'error' => 'Invalid request'));
}
?>


**../includes/header.php**

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فواتير الفندق</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
</head>
<body>
    <header class="bg-gray-800 text-white p-4">
        <h1 class="text-lg font-bold">فواتير الفندق</h1>
    </header>
    <div class="container mx-auto p-4">
        <?php echo $content; ?>
    </div>
</body>
</html>


**../includes/footer.php**

<footer class="bg-gray-800 text-white p-4">
    <p>&copy; 2023 فواتير الفندق</p>
</footer>


**../config/database.php**

<?php
// Database connection settings
$host = 'localhost';
$dbname = 'fawateer_al_fondok';
$username = 'root';
$password = '';

// Create database connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


**../js/script.js**
javascript
// Get form element
const form = document.getElementById('create-form');

// Add event listener to form submission
form.addEventListener('submit', (e) => {
    e.preventDefault();

    // Get form data
    const formData = new FormData(form);

    // Send AJAX request to backend
    fetch('../backend/فواتير-الفندق.php', {
        method: 'POST',
        body: formData,
    })
    .then((response) => response.json())
    .then((data) => {
        if (data.success) {
            // Redirect back to list_{mod_slug}.php
            window.location.href = 'list_فواتير-الفندق.php';
        } else {
            // Display error message
            alert(data.error);
        }
    })
    .catch((error) => {
        console.error(error);
    });
});