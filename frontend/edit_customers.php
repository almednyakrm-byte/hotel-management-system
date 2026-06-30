**edit_customers.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get customer ID from URL
$id = $_GET['id'];

// Fetch customer details via AJAX
$customer = json_decode(file_get_contents('../backend/customers.php?id=' . $id), true);

// Check if customer exists
if (empty($customer)) {
    echo 'Customer not found';
    exit;
}

// Set page title
$page_title = 'Edit Customer';

// Include header
include 'header.php';

?>

<!-- Page content -->
<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-12">
    <h1 class="text-3xl font-bold text-gray-900 mb-4"><?= $page_title ?></h1>

    <!-- Form -->
    <form id="edit-customer-form" class="bg-white rounded shadow-md p-4">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" id="name" name="name" class="block w-full px-4 py-2 text-sm text-gray-700 bg-gray-200 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" value="<?= $customer['name'] ?>">
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email" class="block w-full px-4 py-2 text-sm text-gray-700 bg-gray-200 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" value="<?= $customer['email'] ?>">
            </div>
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                <input type="tel" id="phone" name="phone" class="block w-full px-4 py-2 text-sm text-gray-700 bg-gray-200 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" value="<?= $customer['phone'] ?>">
            </div>
            <div>
                <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                <textarea id="address" name="address" class="block w-full px-4 py-2 text-sm text-gray-700 bg-gray-200 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"><?= $customer['address'] ?></textarea>
            </div>
        </div>

        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update Customer</button>
    </form>
</div>

<!-- Include footer -->
<?php include 'footer.php'; ?>

<script>
    // Fetch customer details via GET
    fetch('../backend/customers.php?id=' + <?= $id ?>)
        .then(response => response.json())
        .then(data => {
            // Populate form fields
            document.getElementById('name').value = data.name;
            document.getElementById('email').value = data.email;
            document.getElementById('phone').value = data.phone;
            document.getElementById('address').value = data.address;
        })
        .catch(error => console.error(error));

    // Handle form submission
    document.getElementById('edit-customer-form').addEventListener('submit', event => {
        event.preventDefault();

        // Get form data
        const formData = new FormData(event.target);

        // Send PUT request to update customer
        fetch('../backend/customers.php', {
            method: 'PUT',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': <?= json_encode($_SESSION['csrf_token']) ?>
            }
        })
            .then(response => response.json())
            .then(data => {
                // Redirect to list page on success
                window.location.href = 'list_customers.php';
            })
            .catch(error => console.error(error));
    });
</script>


**header.php**

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
</head>
<body>
    <!-- Page content -->
    <div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-12">
        <!-- ... -->


**footer.php**

    </div>
</body>
</html>


**customers.php (backend)**

<?php
// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get customer ID from URL
$id = $_GET['id'];

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get customer details
$stmt = $conn->prepare("SELECT * FROM customers WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch customer details
$customer = $result->fetch_assoc();

// Close database connection
$conn->close();

// Output customer details as JSON
echo json_encode($customer);
?>


**customers.php (update)**

<?php
// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get customer ID from URL
$id = $_GET['id'];

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get customer details
$stmt = $conn->prepare("SELECT * FROM customers WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch customer details
$customer = $result->fetch_assoc();

// Update customer details
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $stmt = $conn->prepare("UPDATE customers SET name = ?, email = ?, phone = ?, address = ? WHERE id = ?");
    $stmt->bind_param("sssss", $name, $email, $phone, $address, $id);
    $stmt->execute();
}

// Close database connection
$conn->close();

// Output success message
echo 'Customer updated successfully';
?>