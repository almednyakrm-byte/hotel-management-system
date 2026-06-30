**create_إدارة-فنادق.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/db.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $address = trim($_POST['address']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);

    // Check for empty fields
    if (empty($name) || empty($address) || empty($phone) || empty($email)) {
        $error = 'Please fill in all fields';
    } else {
        // Insert data into database
        $query = "INSERT INTO إدارة_فنادق (name, address, phone, email) VALUES ('$name', '$address', '$phone', '$email')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            // Redirect back to list page
            header('Location: list_إدارة-فنادق.php');
            exit;
        } else {
            $error = 'Error inserting data';
        }
    }
}

// Include header
require_once '../includes/header.php';

?>

<!-- Main content -->
<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-12">
    <div class="flex justify-center">
        <div class="w-full xl:w-3/4 lg:w-11/12 p-5 pt-6 m-4 bg-white rounded-lg border border-gray-200 shadow-md sm:p-6 sm:pb-8 sm:pt-6 sm:rounded-t-lg">
            <h1 class="text-center text-2xl font-bold text-gray-900">إضافة إدارة فندق جديدة</h1>
            <form action="" method="post" class="space-y-6">
                <?php if (isset($error)) : ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded text-sm">
                        <?= $error ?>
                    </div>
                <?php endif; ?>
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">اسم الإدارة</label>
                    <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                </div>
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700">عنوان الإدارة</label>
                    <input type="text" name="address" id="address" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                </div>
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">رقم الهاتف</label>
                    <input type="tel" name="phone" id="phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">البريد الإلكتروني</label>
                    <input type="email" name="email" id="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                </div>
                <button type="submit" name="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">إضافة</button>
            </form>
        </div>
    </div>
</div>

<!-- Include footer -->
<?php require_once '../includes/footer.php'; ?>


**create_إدارة-فنادق.js**
javascript
// Get form elements
const form = document.querySelector('form');
const submitButton = document.querySelector('button[type="submit"]');

// Add event listener to form submission
form.addEventListener('submit', (e) => {
    e.preventDefault();
    const formData = new FormData(form);
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../backend/إدارة-فنادق.php', true);
    xhr.onload = () => {
        if (xhr.status === 200) {
            window.location.href = 'list_إدارة-فنادق.php';
        } else {
            console.error(xhr.responseText);
        }
    };
    xhr.send(formData);
});


**../backend/إدارة-فنادق.php**

<?php
// Include database connection
require_once '../config/db.php';

// Check if form data has been sent
if (isset($_POST['name']) && isset($_POST['address']) && isset($_POST['phone']) && isset($_POST['email'])) {
    // Insert data into database
    $name = trim($_POST['name']);
    $address = trim($_POST['address']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);

    $query = "INSERT INTO إدارة_فنادق (name, address, phone, email) VALUES ('$name', '$address', '$phone', '$email')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo 'Data inserted successfully';
        http_response_code(200);
    } else {
        echo 'Error inserting data';
        http_response_code(500);
    }
} else {
    echo 'Invalid request';
    http_response_code(400);
}