**edit_فنادق.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via AJAX
$js = "
    $(document).ready(function() {
        $.ajax({
            type: 'GET',
            url: '../backend/فنادق.php?id=" . $id . "',
            dataType: 'json',
            success: function(data) {
                $('#name').val(data.name);
                $('#address').val(data.address);
                $('#phone').val(data.phone);
            }
        });
    });
";

// Include JavaScript code
echo '<script>' . $js . '</script>';

// Form HTML
?>

<div class="max-w-md mx-auto p-8 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-bold text-slate-900 mb-4">Edit Hotel</h2>
    <form id="edit-form" class="space-y-4">
        <div>
            <label for="name" class="block text-sm font-medium text-slate-900">Name</label>
            <input type="text" id="name" name="name" class="block w-full p-2 pl-10 text-sm text-slate-900 placeholder-slate-400 border border-slate-300 rounded-md focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
        </div>
        <div>
            <label for="address" class="block text-sm font-medium text-slate-900">Address</label>
            <input type="text" id="address" name="address" class="block w-full p-2 pl-10 text-sm text-slate-900 placeholder-slate-400 border border-slate-300 rounded-md focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
        </div>
        <div>
            <label for="phone" class="block text-sm font-medium text-slate-900">Phone</label>
            <input type="text" id="phone" name="phone" class="block w-full p-2 pl-10 text-sm text-slate-900 placeholder-slate-400 border border-slate-300 rounded-md focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
        </div>
        <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-indigo-500 border border-indigo-500 rounded-md hover:bg-indigo-700 focus:outline-none focus:border-indigo-700 focus:ring-1 focus:ring-indigo-700">Save Changes</button>
    </form>
</div>

<?php
// JavaScript code for form submission
$js = "
    $(document).ready(function() {
        $('#edit-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'PUT',
                url: '../backend/فنادق.php',
                data: formData,
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        window.location.href = 'list_فنادق.php';
                    } else {
                        alert('Error: ' + data.message);
                    }
                }
            });
        });
    });
";

// Include JavaScript code
echo '<script>' . $js . '</script>';
?>


**backend/فنادق.php**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

// Get ID
$id = $_GET['id'];

// Fetch existing record details
$query = "SELECT * FROM فنادق WHERE id = '$id'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

// Update record
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents('php://input'), $data);
    $query = "UPDATE فنادق SET name = '$data[name]', address = '$data[address]', phone = '$data[phone]' WHERE id = '$id'";
    $result = mysqli_query($conn, $query);
    if ($result) {
        echo json_encode(array('success' => true));
    } else {
        echo json_encode(array('success' => false, 'message' => 'Error updating record'));
    }
} else {
    echo json_encode($row);
}
?>