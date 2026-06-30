**create_غرف-فنادق.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/database.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);
    $capacity = trim($_POST['capacity']);
    $amenities = trim($_POST['amenities']);

    // Check if all fields are filled
    if (!empty($name) && !empty($description) && !empty($price) && !empty($capacity) && !empty($amenities)) {
        // Insert data into database
        $query = "INSERT INTO غرف_فنادق (name, description, price, capacity, amenities) VALUES ('$name', '$description', '$price', '$capacity', '$amenities')";
        $result = mysqli_query($conn, $query);

        // Check if data has been inserted successfully
        if ($result) {
            // Redirect back to list page
            header('Location: list_غرف-فنادق.php');
            exit;
        } else {
            // Display error message
            echo '<div class="alert alert-danger">Error inserting data.</div>';
        }
    } else {
        // Display error message
        echo '<div class="alert alert-danger">Please fill all fields.</div>';
    }
}

// Include header
require_once '../includes/header.php';

// Include form
?>

<div class="container mx-auto p-4 mt-12">
    <h1 class="text-3xl font-bold mb-4">Create New غرف_فنادق</h1>

    <form id="create-form" method="post">
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Name:</label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name" type="text" name="name" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="description">Description:</label>
            <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="description" name="description" required></textarea>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="price">Price:</label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="price" type="number" name="price" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="capacity">Capacity:</label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="capacity" type="number" name="capacity" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="amenities">Amenities:</label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="amenities" type="text" name="amenities" required>
        </div>

        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit" name="submit">Create</button>
    </form>
</div>

<?php
// Include footer
require_once '../includes/footer.php';
?>

<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/غرف-فنادق.php',
                data: $(this).serialize(),
                success: function(data) {
                    if (data == 'success') {
                        window.location.href = 'list_غرف-فنادق.php';
                    } else {
                        alert('Error creating غرف_فنادق');
                    }
                }
            });
        });
    });
</script>


**backend/غرف-فنادق.php**

<?php
// Include database connection
require_once '../config/database.php';

// Check if form data has been sent
if (isset($_POST['submit'])) {
    // Insert data into database
    $query = "INSERT INTO غرف_فنادق (name, description, price, capacity, amenities) VALUES ('".$_POST['name']."', '".$_POST['description']."', '".$_POST['price']."', '".$_POST['capacity']."', '".$_POST['amenities']."')";
    $result = mysqli_query($conn, $query);

    // Check if data has been inserted successfully
    if ($result) {
        echo 'success';
    } else {
        echo 'Error inserting data.';
    }
}