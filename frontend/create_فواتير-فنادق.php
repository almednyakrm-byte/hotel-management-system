**create_فواتير-فنادق.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/db.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form data
    $errors = [];
    $data = [
        'اسم_العميل' => $_POST['اسم_العميل'],
        'تاريخ_الفاتورة' => $_POST['تاريخ_الفاتورة'],
        'مبلغ_الفاتورة' => $_POST['مبلغ_الفاتورة'],
        'تاريخ_الاستحقاق' => $_POST['تاريخ_الاستحقاق'],
        'حالة_الفاتورة' => $_POST['حالة_الفاتورة'],
    ];

    // Check for empty fields
    foreach ($data as $field => $value) {
        if (empty($value)) {
            $errors[] = $field;
        }
    }

    // Check for valid date formats
    if (!preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $_POST['تاريخ_الفاتورة'])) {
        $errors[] = 'تاريخ_الفاتورة';
    }
    if (!preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $_POST['تاريخ_الاستحقاق'])) {
        $errors[] = 'تاريخ_الاستحقاق';
    }

    // If no errors, insert data into database
    if (empty($errors)) {
        $sql = "INSERT INTO فواتير_فنادق (اسم_العميل, تاريخ_الفاتورة, مبلغ_الفاتورة, تاريخ_الاستحقاق, حالة_الفاتورة) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$data['اسم_العميل'], $data['تاريخ_الفاتورة'], $data['مبلغ_الفاتورة'], $data['تاريخ_الاستحقاق'], $data['حالة_الفاتورة']]);
        header('Location: list_فواتير-فنادق.php');
        exit;
    } else {
        // Display errors
        $error_message = 'Please fill in all required fields.';
        foreach ($errors as $error) {
            $error_message .= ' ' . $error . ' ';
        }
    }
}

// Include header
require_once '../includes/header.php';

// Include form
?>

<div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
    <h1 class="text-3xl font-bold mb-4">create_فواتير-فنادق</h1>
    <form id="create-form" method="POST">
        <div class="mb-4">
            <label for="اسم_العميل" class="block text-sm font-medium text-gray-700">اسم_العميل</label>
            <input type="text" id="اسم_العميل" name="اسم_العميل" class="block w-full p-2 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>
        <div class="mb-4">
            <label for="تاريخ_الفاتورة" class="block text-sm font-medium text-gray-700">تاريخ_الفاتورة</label>
            <input type="date" id="تاريخ_الفاتورة" name="تاريخ_الفاتورة" class="block w-full p-2 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>
        <div class="mb-4">
            <label for="مبلغ_الفاتورة" class="block text-sm font-medium text-gray-700">مبلغ_الفاتورة</label>
            <input type="number" id="مبلغ_الفاتورة" name="مبلغ_الفاتورة" class="block w-full p-2 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>
        <div class="mb-4">
            <label for="تاريخ_الاستحقاق" class="block text-sm font-medium text-gray-700">تاريخ_الاستحقاق</label>
            <input type="date" id="تاريخ_الاستحقاق" name="تاريخ_الاستحقاق" class="block w-full p-2 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>
        <div class="mb-4">
            <label for="حالة_الفاتورة" class="block text-sm font-medium text-gray-700">حالة_الفاتورة</label>
            <select id="حالة_الفاتورة" name="حالة_الفاتورة" class="block w-full p-2 mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                <option value="">Select...</option>
                <option value="paid">paid</option>
                <option value="pending">pending</option>
                <option value="cancelled">cancelled</option>
            </select>
        </div>
        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Submit</button>
    </form>
</div>

<?php
// Include footer
require_once '../includes/footer.php';
?>

<script>
    $(document).ready(function() {
        $('#create-form').submit(function(event) {
            event.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/فواتير-فنادق.php',
                data: $(this).serialize(),
                success: function(response) {
                    window.location.href = 'list_فواتير-فنادق.php';
                },
                error: function(xhr, status, error) {
                    console.error(xhr, status, error);
                }
            });
        });
    });
</script>

**backend/فواتير-فنادق.php**

<?php
// Include database connection
require_once '../config/db.php';

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form data
    $data = [
        'اسم_العميل' => $_POST['اسم_العميل'],
        'تاريخ_الفاتورة' => $_POST['تاريخ_الفاتورة'],
        'مبلغ_الفاتورة' => $_POST['مبلغ_الفاتورة'],
        'تاريخ_الاستحقاق' => $_POST['تاريخ_الاستحقاق'],
        'حالة_الفاتورة' => $_POST['حالة_الفاتورة'],
    ];

    // Insert data into database
    $sql = "INSERT INTO فواتير_فنادق (اسم_العميل, تاريخ_الفاتورة, مبلغ_الفاتورة, تاريخ_الاستحقاق, حالة_الفاتورة) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$data['اسم_العميل'], $data['تاريخ_الفاتورة'], $data['مبلغ_الفاتورة'], $data['تاريخ_الاستحقاق'], $data['حالة_الفاتورة']]);

    // Return success message
    echo 'success';
}