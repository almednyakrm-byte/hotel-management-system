**edit_فواتير-فنادق.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/فواتير-فنادق.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Check if data exists
if ($data) {
    $title = $data['title'];
    $description = $data['description'];
    $amount = $data['amount'];
    $hotel_id = $data['hotel_id'];
} else {
    echo 'Error fetching data';
    exit;
}

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل فاتورة فنادق</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
        <h2 class="text-lg font-bold mb-4">تعديل فاتورة فنادق</h2>
        <form id="edit-form">
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700">العنوان</label>
                <input type="text" id="title" name="title" class="block w-full p-2 mt-1 border-gray-300 rounded-md" value="<?= $title ?>">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">الوصف</label>
                <textarea id="description" name="description" class="block w-full p-2 mt-1 border-gray-300 rounded-md"><?= $description ?></textarea>
            </div>
            <div class="mb-4">
                <label for="amount" class="block text-sm font-medium text-gray-700">المبلغ</label>
                <input type="number" id="amount" name="amount" class="block w-full p-2 mt-1 border-gray-300 rounded-md" value="<?= $amount ?>">
            </div>
            <div class="mb-4">
                <label for="hotel_id" class="block text-sm font-medium text-gray-700">رقم الفندق</label>
                <input type="number" id="hotel_id" name="hotel_id" class="block w-full p-2 mt-1 border-gray-300 rounded-md" value="<?= $hotel_id ?>">
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">تعديل</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/فواتير-فنادق.php',
                    data: formData,
                    success: function(response) {
                        if (response === 'success') {
                            window.location.href = 'list_<?= $_SESSION['mod_slug'] ?>.php';
                        } else {
                            alert('Error updating record');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/فواتير-فنادق.php**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    echo 'Error: ID not set';
    exit;
}

// Get ID
$id = $_GET['id'];

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch existing record details
$query = "SELECT * FROM فواتير_فنادق WHERE id = '$id'";
$result = $conn->query($query);

// Check if record exists
if ($result->num_rows > 0) {
    // Fetch record details
    $row = $result->fetch_assoc();
    $title = $row['title'];
    $description = $row['description'];
    $amount = $row['amount'];
    $hotel_id = $row['hotel_id'];

    // Output record details as JSON
    echo json_encode(array(
        'title' => $title,
        'description' => $description,
        'amount' => $amount,
        'hotel_id' => $hotel_id
    ));
} else {
    echo 'Error: Record not found';
}

// Close connection
$conn->close();
?>


**backend/edit_فواتير-فنادق.php**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    echo 'Error: ID not set';
    exit;
}

// Get ID
$id = $_GET['id'];

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if request method is PUT
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Get form data
    $title = $_POST['title'];
    $description = $_POST['description'];
    $amount = $_POST['amount'];
    $hotel_id = $_POST['hotel_id'];

    // Update record
    $query = "UPDATE فواتير_فنادق SET title = '$title', description = '$description', amount = '$amount', hotel_id = '$hotel_id' WHERE id = '$id'";
    $result = $conn->query($query);

    // Check if update was successful
    if ($result) {
        echo 'success';
    } else {
        echo 'Error updating record';
    }
} else {
    echo 'Error: Invalid request method';
}

// Close connection
$conn->close();
?>