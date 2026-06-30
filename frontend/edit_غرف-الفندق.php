**edit_غرف-الفندق.php**

<?php
// Session validation
if (!isset($_SESSION['mod_slug'])) {
    header('Location: index.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/غرف-الفندق.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Check if data exists
if (empty($data)) {
    echo 'Error: Record not found.';
    exit;
}

// Set page title and mod slug
$page_title = 'تعديل غرفة فندق';
$mod_slug = $_SESSION['mod_slug'];

// Include header and footer
include 'header.php';
?>

<div class="container mx-auto p-4">
    <h1 class="text-3xl font-bold mb-4"><?= $page_title ?></h1>
    <form id="edit-form" class="bg-white p-4 rounded shadow-md">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">اسم الغرفة</label>
                <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-sm text-gray-700 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" value="<?= $data['name'] ?>">
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">وصف الغرفة</label>
                <textarea id="description" name="description" class="block w-full p-2 mt-1 text-sm text-gray-700 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"><?= $data['description'] ?></textarea>
            </div>
            <div>
                <label for="price" class="block text-sm font-medium text-gray-700">سعر الغرفة</label>
                <input type="number" id="price" name="price" class="block w-full p-2 mt-1 text-sm text-gray-700 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" value="<?= $data['price'] ?>">
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">حالة الغرفة</label>
                <select id="status" name="status" class="block w-full p-2 mt-1 text-sm text-gray-700 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    <option value="active" <?= $data['status'] == 'active' ? 'selected' : '' ?>>نشط</option>
                    <option value="inactive" <?= $data['status'] == 'inactive' ? 'selected' : '' ?>>غير نشط</option>
                </select>
            </div>
        </div>
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">تعديل</button>
    </form>
</div>

<script>
    // Fetch existing record details via GET
    fetch('../backend/غرف-الفندق.php?id=<?= $id ?>')
        .then(response => response.json())
        .then(data => {
            // Populate form fields
            document.getElementById('name').value = data.name;
            document.getElementById('description').value = data.description;
            document.getElementById('price').value = data.price;
            document.getElementById('status').value = data.status;
        })
        .catch(error => console.error(error));

    // Handle form submission
    document.getElementById('edit-form').addEventListener('submit', event => {
        event.preventDefault();
        const formData = new FormData(event.target);
        fetch('../backend/غرف-الفندق.php', {
            method: 'PUT',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'list_<?= $mod_slug ?>.php';
                } else {
                    console.error(data.error);
                }
            })
            .catch(error => console.error(error));
    });
</script>

<?php
include 'footer.php';
?>


**backend/غرف-الفندق.php**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    echo json_encode(['error' => 'ID not set']);
    exit;
}

// Get ID
$id = $_GET['id'];

// Fetch existing record details from database
// Replace with your database query
$data = [
    'name' => 'غرفة فندق',
    'description' => 'وصف الغرفة',
    'price' => 100,
    'status' => 'active'
];

// Return data as JSON
echo json_encode($data);
?>


Note: This code assumes you have a `header.php` and `footer.php` file that includes the HTML header and footer respectively. You should replace the database query in the `backend/غرف-الفندق.php` file with your actual database query.