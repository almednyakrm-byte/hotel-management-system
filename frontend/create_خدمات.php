**create_خدمات.php**

<?php
// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
require_once 'header.php';
require_once 'navigation.php';

// Define form fields
$formFields = [
    'name' => [
        'label' => 'اسم الخدمة',
        'type' => 'text',
        'placeholder' => 'اسم الخدمة',
        'required' => true,
    ],
    'description' => [
        'label' => 'وصف الخدمة',
        'type' => 'textarea',
        'placeholder' => 'وصف الخدمة',
        'required' => true,
    ],
    'price' => [
        'label' => 'سعر الخدمة',
        'type' => 'number',
        'placeholder' => 'سعر الخدمة',
        'required' => true,
    ],
];

// Display form
?>

<div class="container mx-auto p-4">
    <h1 class="text-3xl font-bold text-slate-900 mb-4">إضافة خدمة جديدة</h1>
    <form id="create-service-form" class="bg-white p-4 rounded shadow-md">
        <?php foreach ($formFields as $field => $properties) : ?>
            <div class="mb-4">
                <label for="<?= $field ?>" class="block text-sm font-medium text-slate-900"><?= $properties['label'] ?></label>
                <input type="<?= $properties['type'] ?>" id="<?= $field ?>" name="<?= $field ?>" class="block w-full p-2 pl-10 text-sm text-gray-700 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" placeholder="<?= $properties['placeholder'] ?>" required>
            </div>
        <?php endforeach; ?>
        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">إضافة خدمة</button>
    </form>
</div>

<script>
    $(document).ready(function() {
        $('#create-service-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/خدمات.php',
                data: $(this).serialize(),
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_خدمات.php';
                    } else {
                        alert('حدث خطأ أثناء إضافة الخدمة');
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


**backend/خدمات.php**

<?php
// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Validate form data
$name = trim($_POST['name']);
$description = trim($_POST['description']);
$price = (int) $_POST['price'];

// Insert new record
$query = "INSERT INTO services (name, description, price) VALUES ('$name', '$description', '$price')";
$result = mysqli_query($conn, $query);

if ($result) {
    echo 'success';
} else {
    echo 'error';
}
?>


Note: This code assumes you have a MySQL database with a table named `services` and columns `name`, `description`, and `price`. You should replace the `../backend/خدمات.php` URL with the actual path to your backend script. Additionally, this code does not include any error handling or security measures, such as input validation and sanitization, which you should add in a production environment.