**edit_مدفوعات.php**

<?php
// Session validation
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/مدفوعات.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Set form fields
$name = $data['name'];
$amount = $data['amount'];
$description = $data['description'];

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل مدفوعات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
        <h2 class="text-lg font-bold text-slate-900 mb-4">تعديل مدفوعات</h2>
        <form id="edit-form">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-slate-900">اسم</label>
                <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" value="<?= $name ?>">
            </div>
            <div class="mb-4">
                <label for="amount" class="block text-sm font-medium text-slate-900">مبلغ</label>
                <input type="number" id="amount" name="amount" class="block w-full p-2 mt-1 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" value="<?= $amount ?>">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-slate-900">وصف</label>
                <textarea id="description" name="description" class="block w-full p-2 mt-1 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" rows="4"><?= $description ?></textarea>
            </div>
            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-500 hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">حفظ</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
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
</body>
</html>

**backend/مدفوعات.php**

<?php
// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details from database
// Replace with your actual database query
$data = array(
    'name' => 'مدفوعات',
    'amount' => 100,
    'description' => 'وصف مدفوعات'
);

// Output JSON response
header('Content-Type: application/json');
echo json_encode($data);