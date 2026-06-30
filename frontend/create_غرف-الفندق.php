**create_غرف-الفندق.php**

<?php
// Session validation
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
require_once 'header.php';
?>

<!-- Page content -->
<div class="container mx-auto p-4 pt-6 md:p-6 lg:px-12 xl:px-24">
    <div class="flex flex-wrap -mx-4">
        <div class="w-full xl:w-8/12 px-4">
            <div class="bg-white rounded shadow-md p-4">
                <h2 class="text-lg font-bold mb-4">إضافة غرفة فندق جديدة</h2>
                <form id="create-form" class="space-y-4">
                    <div class="flex flex-wrap -mx-4">
                        <div class="w-full xl:w-6/12 px-4">
                            <label for="name" class="block text-sm font-bold mb-2">اسم الغرفة</label>
                            <input type="text" id="name" name="name" class="block w-full px-4 py-2 text-sm text-gray-700 border rounded" required>
                        </div>
                        <div class="w-full xl:w-6/12 px-4">
                            <label for="capacity" class="block text-sm font-bold mb-2">السعة</label>
                            <input type="number" id="capacity" name="capacity" class="block w-full px-4 py-2 text-sm text-gray-700 border rounded" required>
                        </div>
                    </div>
                    <div class="flex flex-wrap -mx-4">
                        <div class="w-full xl:w-6/12 px-4">
                            <label for="price" class="block text-sm font-bold mb-2">السعر</label>
                            <input type="number" id="price" name="price" class="block w-full px-4 py-2 text-sm text-gray-700 border rounded" required>
                        </div>
                        <div class="w-full xl:w-6/12 px-4">
                            <label for="amenities" class="block text-sm font-bold mb-2">المزايا</label>
                            <textarea id="amenities" name="amenities" class="block w-full px-4 py-2 text-sm text-gray-700 border rounded" required></textarea>
                        </div>
                    </div>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">إضافة</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Include footer -->
<?php require_once 'footer.php'; ?>


**create_غرف-الفندق.js**
javascript
// AJAX form submission
document.getElementById('create-form').addEventListener('submit', function(event) {
    event.preventDefault();
    var formData = new FormData(this);
    $.ajax({
        type: 'POST',
        url: '../backend/غرف-الفندق.php',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response === 'success') {
                window.location.href = 'list_غرف-الفندق.php';
            } else {
                alert('Error: ' + response);
            }
        }
    });
});


**backend/غرف-الفندق.php**

<?php
// Database connection
require_once 'db.php';

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate form data
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $capacity = filter_var($_POST['capacity'], FILTER_SANITIZE_NUMBER_INT);
    $price = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
    $amenities = filter_var($_POST['amenities'], FILTER_SANITIZE_STRING);

    // Insert data into database
    $query = "INSERT INTO غرف_الفندق (name, capacity, price, amenities) VALUES ('$name', '$capacity', '$price', '$amenities')";
    $result = mysqli_query($conn, $query);

    // Check if data is inserted successfully
    if ($result) {
        echo 'success';
    } else {
        echo 'Error: ' . mysqli_error($conn);
    }
}
?>