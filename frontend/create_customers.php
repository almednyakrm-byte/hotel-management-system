**create_customers.php**

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
include 'navigation.php';
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-12 2xl:p-12">
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6 lg:p-8 xl:p-8 2xl:p-8">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Create Customer</h2>
        <form id="create-customer-form">
            <div class="mb-4">
                <label for="name" class="block text-sm font-bold text-gray-700 mb-2">Name</label>
                <input type="text" id="name" name="name" class="bg-gray-200 border border-gray-200 text-gray-700 py-1 px-2 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-bold text-gray-700 mb-2">Email</label>
                <input type="email" id="email" name="email" class="bg-gray-200 border border-gray-200 text-gray-700 py-1 px-2 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-sm font-bold text-gray-700 mb-2">Phone</label>
                <input type="tel" id="phone" name="phone" class="bg-gray-200 border border-gray-200 text-gray-700 py-1 px-2 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div class="mb-4">
                <label for="address" class="block text-sm font-bold text-gray-700 mb-2">Address</label>
                <textarea id="address" name="address" class="bg-gray-200 border border-gray-200 text-gray-700 py-1 px-2 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500" required></textarea>
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">Create Customer</button>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#create-customer-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/customers.php',
                data: formData,
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_customers.php';
                    } else {
                        alert('Error creating customer');
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


**customers.php (backend)**

<?php
// Include database connection
include 'db.php';

// Check if form data is submitted
if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['phone']) && isset($_POST['address'])) {
    // Prepare SQL query
    $sql = "INSERT INTO customers (name, email, phone, address) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $_POST['name'], $_POST['email'], $_POST['phone'], $_POST['address']);
    $stmt->execute();
    $stmt->close();
    echo 'success';
} else {
    echo 'Error creating customer';
}
?>


**header.php, navigation.php, footer.php (backend)**

<!-- header.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
</head>
<body>
    <!-- navigation.php -->
    <nav class="bg-gray-800 text-white p-4">
        <ul class="flex justify-between items-center">
            <li><a href="index.php" class="text-lg font-bold">Home</a></li>
            <li><a href="list_customers.php" class="text-lg font-bold">Customers</a></li>
            <li><a href="create_customer.php" class="text-lg font-bold">Create Customer</a></li>
            <li><a href="logout.php" class="text-lg font-bold">Logout</a></li>
        </ul>
    </nav>
    <!-- footer.php -->
    <footer class="bg-gray-800 text-white p-4">
        <p>&copy; 2023 Customers</p>
    </footer>
</body>
</html>


Note: This code assumes you have a database connection established in `db.php` and a table named `customers` with columns `name`, `email`, `phone`, and `address`. You will need to modify the code to fit your specific database schema and backend setup.