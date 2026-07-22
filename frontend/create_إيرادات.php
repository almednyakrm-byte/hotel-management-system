**create_إيرادات.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Include header
include 'header.php';

// Include navigation
include 'navigation.php';

?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:px-12">
    <div class="bg-white rounded-lg shadow-md p-4">
        <h2 class="text-slate-900 font-bold text-lg mb-4">إضافة إيرادات جديدة</h2>
        <form id="create-إيرادات-form" class="space-y-4">
            <div>
                <label for="إيرادات_تاريخ" class="text-slate-900 font-bold">تاريخ الإيرادات</label>
                <input type="date" id="إيرادات_تاريخ" name="إيرادات_تاريخ" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500">
            </div>
            <div>
                <label for="إيرادات_المبلغ" class="text-slate-900 font-bold">مبلغ الإيرادات</label>
                <input type="number" id="إيرادات_المبلغ" name="إيرادات_المبلغ" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500">
            </div>
            <div>
                <label for="إيرادات_المصدر" class="text-slate-900 font-bold">مصدر الإيرادات</label>
                <input type="text" id="إيرادات_المصدر" name="إيرادات_المصدر" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500">
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">إضافة إيرادات</button>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#create-إيرادات-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/إيرادات.php',
                data: formData,
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_إيرادات.php';
                    } else {
                        alert('Error adding إيرادات');
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


**backend/إيرادات.php**

<?php
// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input data
    $إيرادات_تاريخ = sanitize($_POST['إيرادات_تاريخ']);
    $إيرادات_المبلغ = sanitize($_POST['إيرادات_المبلغ']);
    $إيرادات_المصدر = sanitize($_POST['إيرادات_المصدر']);

    // Insert data into database
    $query = "INSERT INTO إيرادات (إيرادات_تاريخ, إيرادات_المبلغ, إيرادات_المصدر) VALUES ('$إيرادات_تاريخ', '$إيرادات_المبلغ', '$إيرادات_المصدر')";
    $result = mysqli_query($conn, $query);

    // Check if data is inserted successfully
    if ($result) {
        echo 'success';
    } else {
        echo 'Error adding إيرادات';
    }
}

// Function to sanitize input data
function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>