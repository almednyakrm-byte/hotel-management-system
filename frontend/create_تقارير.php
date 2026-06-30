**create_تقارير.php**

<?php
// Session validation
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
require_once 'header.php';
require_once 'navigation.php';
?>

<div class="container mx-auto p-4 pt-6">
    <h1 class="text-3xl font-bold text-slate-900 mb-4">إضافة تقرير جديد</h1>

    <form id="create-report-form" class="bg-white rounded-lg shadow-md p-4">
        <div class="grid grid-cols-1 gap-4 mb-4">
            <label for="title" class="block text-sm font-medium text-slate-900">عنوان التقرير</label>
            <input type="text" id="title" name="title" class="block w-full p-2 text-sm text-gray-900 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>
        <div class="grid grid-cols-1 gap-4 mb-4">
            <label for="description" class="block text-sm font-medium text-slate-900">وصف التقرير</label>
            <textarea id="description" name="description" class="block w-full p-2 text-sm text-gray-900 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required></textarea>
        </div>
        <div class="grid grid-cols-1 gap-4 mb-4">
            <label for="date" class="block text-sm font-medium text-slate-900">تاريخ التقرير</label>
            <input type="date" id="date" name="date" class="block w-full p-2 text-sm text-gray-900 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>
        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">حفظ التقرير</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('#create-report-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/تقارير.php',
                data: formData,
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_تقارير.php';
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
require_once 'footer.php';
?>


**backend/تقارير.php**

<?php
// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate form data
    $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
    $date = filter_var($_POST['date'], FILTER_SANITIZE_STRING);

    // Insert data into database
    $db = new PDO('dsn', 'username', 'password');
    $stmt = $db->prepare('INSERT INTO تقارير (title, description, date) VALUES (:title, :description, :date)');
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':date', $date);
    $stmt->execute();

    // Redirect to list page
    header('Location: list_تقارير.php');
    exit;
} else {
    // Handle invalid request
    echo 'Error: Invalid request';
}
?>