**create_مدفوعات.php**

<?php
// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
include 'header.php';
include 'navigation.php';
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12">
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6 lg:p-8">
        <h2 class="text-slate-900 text-lg font-bold mb-4">إضافة مدفوعات جديدة</h2>
        <form id="create-medfa3at-form" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="text-slate-900 text-sm font-bold">اسم المدفوعات:</label>
                    <input type="text" id="name" name="name" class="w-full p-2 text-slate-900 border border-slate-200 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label for="amount" class="text-slate-900 text-sm font-bold">مبلغ المدفوعات:</label>
                    <input type="number" id="amount" name="amount" class="w-full p-2 text-slate-900 border border-slate-200 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>
            <div>
                <label for="date" class="text-slate-900 text-sm font-bold">تاريخ المدفوعات:</label>
                <input type="date" id="date" name="date" class="w-full p-2 text-slate-900 border border-slate-200 rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">إضافة</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('#create-medfa3at-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/مدفوعات.php',
                data: formData,
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_مدفوعات.php';
                    } else {
                        alert('Error: ' + response);
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


**backend/مدفوعات.php**

<?php
// Check if form data is submitted
if (isset($_POST['name']) && isset($_POST['amount']) && isset($_POST['date'])) {
    // Insert data into database
    $name = $_POST['name'];
    $amount = $_POST['amount'];
    $date = $_POST['date'];

    // Database connection
    $conn = new mysqli('localhost', 'username', 'password', 'database');

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query
    $sql = "INSERT INTO medfa3at (name, amount, date) VALUES ('$name', '$amount', '$date')";

    // Execute query
    if ($conn->query($sql) === TRUE) {
        echo 'success';
    } else {
        echo 'Error: ' . $sql . '<br>' . $conn->error;
    }

    // Close connection
    $conn->close();
}
?>