**edit_تقارير.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get the ID of the record to be updated
$id = $_GET['id'];

// Fetch the existing record details
$record = json_decode(file_get_contents('../backend/تقارير.php?id=' . $id), true);

// Check if the record exists
if (empty($record)) {
    echo 'Record not found';
    exit;
}

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل تقرير</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-md shadow-md">
        <h1 class="text-2xl font-bold text-slate-900 mb-4">تعديل تقرير</h1>
        <form id="edit-form">
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-slate-900">عنوان التقرير</label>
                <input type="text" id="title" name="title" class="block w-full p-2 text-sm text-gray-900 rounded-md border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" value="<?= $record['title'] ?>">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-slate-900">وصف التقرير</label>
                <textarea id="description" name="description" class="block w-full p-2 text-sm text-gray-900 rounded-md border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" rows="4"><?= $record['description'] ?></textarea>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md">حفظ التغييرات</button>
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
                    url: '../backend/تقارير.php',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
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


**backend/تقارير.php**

<?php
// Check if the ID is set
if (!isset($_GET['id'])) {
    echo json_encode(array('error' => 'ID not set'));
    exit;
}

// Get the ID of the record to be updated
$id = $_GET['id'];

// Connect to the database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to get the record details
$query = "SELECT * FROM تقارير WHERE id = '$id'";
$result = $conn->query($query);

// Check if the record exists
if ($result->num_rows > 0) {
    // Fetch the record details
    $record = $result->fetch_assoc();
    echo json_encode($record);
} else {
    echo json_encode(array('error' => 'Record not found'));
}

// Close the database connection
$conn->close();
?>


**backend/edit_تقارير.php**

<?php
// Check if the ID is set
if (!isset($_GET['id'])) {
    echo json_encode(array('error' => 'ID not set'));
    exit;
}

// Get the ID of the record to be updated
$id = $_GET['id'];

// Connect to the database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the record exists
$query = "SELECT * FROM تقارير WHERE id = '$id'";
$result = $conn->query($query);

// Check if the record exists
if ($result->num_rows > 0) {
    // Fetch the record details
    $record = $result->fetch_assoc();

    // Update the record
    $title = $_POST['title'];
    $description = $_POST['description'];

    $query = "UPDATE تقارير SET title = '$title', description = '$description' WHERE id = '$id'";
    $conn->query($query);

    // Check if the update was successful
    if ($conn->affected_rows > 0) {
        echo json_encode(array('success' => true));
    } else {
        echo json_encode(array('error' => 'Update failed'));
    }
} else {
    echo json_encode(array('error' => 'Record not found'));
}

// Close the database connection
$conn->close();
?>